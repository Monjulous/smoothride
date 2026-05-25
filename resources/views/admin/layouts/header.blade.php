<!-- Header (desktop sidebar toggle lives on sidebar edge — admin-UI) -->
<div class="header admin-shell-header">
    <?php
    //use DB;
    ?>

    <div class="top-nav-search d-none d-md-block">
        <form action="javascript:void(0);">
            <span class="search-icon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Search ride IDs, drivers, or analytics...">
        </form>
    </div>

    <!-- Mobile Menu Toggle -->
    <a class="mobile_btn" id="mobile_btn">
        <i class="fa fa-bars"></i>
    </a>
    <!-- /Mobile Menu Toggle -->

    <!-- Header Right Menu -->
    <ul class="nav user-menu">

        {{-- <!-- Frontend -->
        <!--   <li class="nav-item">
            <a href="{{ route('home') }}" target="_blank" class="dropdown-toggle nav-link" title="Front End">
                <i data-feather="cast"></i>
            </a>
        </li> -->
        <!-- /Frontend --> --}}
        <?php
        
        $noti = DB::table('notifications')->join('users', 'users.id', '=', 'notifications.user_id')->select('notifications.id', 'users.name', 'users.lname', 'users.image', 'notifications.messages', 'notifications.created_at')->where('notifications.seen', 0)->orderBy('notifications.id', 'desc')->get()->toArray();
        
        $count = DB::table('notifications')->where('seen', 0)->count();
        
        ?>
        <!-- Notifications -->
        <!-- <li class="nav-item dropdown noti-dropdown">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                <i class="fe fe-bell"></i><span class="count"><?php echo @$count; ?></span>
              
            </a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">Notifications</span>
                    <a href="javascript:void(0)" class="clear-noti" onclick="clear_all();"> Clear All </a>
                </div>


                <div class="noti-content">
                    <ul class="notification-list">
                        <?php 
                              
                              if(count($noti)>0)
                              {
                                foreach ($noti as $key => $value) {
                                    ?>
                        <li class="notification-message">
                            <a href="#">
                                <div class="media">
                                    <span class="avatar avatar-sm">
                                        <img class="avatar-img rounded-circle" alt="User Image"
                                            src="{{ asset(@$value->image) }}">
                                    </span>
                                    <div class="media-body">
                                        <p class="noti-details"><span class="noti-title">{{ @$value->name }}
                                                {{ @$value->lname }}</span> <span class="noti-title">
                                                {{ @$value->messages }}</span></p>
                                        <p class="noti-time"><span class="notification-time"><?php echo date('Y-m-d H:i a', strtotime($value->created_at)); ?></span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <?php
                                }
                              }
                              else
                              {
                                 echo "No Result Found";
                              }
 
                          ?>


                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                </div>
            </div>
        </li> -->


        <li class="nav-item dropdown noti-dropdown">
            <a href="#" id="notificationBell" class="dropdown-toggle nav-link admin-noti-bell" data-toggle="dropdown" onclick="markAllSeen()">
                <span class="admin-noti-bell-inner">
                    <i class="fe fe-bell"></i>
                    <span class="count" id="notification-count">0</span>
                </span>
            </a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">Notifications</span>
                    <a href="javascript:void(0)" class="clear-noti" onclick="clear_all();"> Clear All </a>
                </div>

                <div class="noti-content">
                    <ul class="notification-list" id="notification-list">
                        <!-- JS will populate this -->
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="{{ route('notifications.index') }}" class="noti-view-all">View all activity</a>
                </div>
            </div>
        </li>
        <!-- /Notifications -->

        <!-- User Menu -->
        <li class="nav-item dropdown has-arrow">
            <a href="#" class="dropdown-toggle nav-link admin-user-toggle" data-toggle="dropdown">
                <span class="welcome-user d-none d-md-inline-flex mr-2">
                    <span class="welcome-label">Welcome,</span>
                    <span class="welcome-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                </span>
                <span class="user-img">
                    <img class="rounded-circle admin-header-avatar" src="{{ Auth::user()->image ? asset(Auth::user()->image) : asset('assets/admin/img/default-user.png') }}"
                        onerror="this.src='{{ asset('assets/admin/img/default-user.png') }}';"
                        alt="Admin">
                </span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin_profile') }}">My Profile</a>
                <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
            </div>
        </li>
        <!-- /User Menu -->

    </ul>
    <!-- /Header Right Menu -->

</div>
<script>
     $(document).ready(function () {
        let userId = '{{ Auth::user()->id }}';
        if (!userId) {
            return;
        }
    });
</script>


<script>
    $(document).ready(function () {
        const userId = '{{ Auth::user()->id }}';
        if (userId) {
            listenToNotifications(userId);
        }
    });

    function listenToNotifications(userId) {
        const notificationRef = firebase.database().ref('Notification/' + userId);
        const $notificationList = $('#notification-list');
        const $notificationCount = $('#notification-count');

        notificationRef.on("value", function (snapshot) {
            $notificationList.empty(); // Clear existing
            let count = 0;

            snapshot.forEach(function (childSnapshot) {
                const notification = childSnapshot.val();
                const notificationKey = childSnapshot.key;

                console.log(notification.seen,'ssss');
                if (!notification.seen) count++;

                const image = notification.senderImage || '{{ asset('assets/admin/img/default-user.png') }}';
                const name = notification.name || 'Unknown';
                const message = notification.message || 'New message';
                const url = notification.url || '#';
                const timestamp = notification.created_at ? notification.created_at : '';

                const listItem = `
                    <li class="notification-message">
                        <a href="${url}" onclick="markSeen('${userId}', '${notificationKey}')" class="noti-item-link">
                            <div class="media noti-item-media">
                                <span class="avatar avatar-sm noti-avatar-wrap">
                                    <img class="avatar-img rounded-circle noti-avatar-img" alt="" src="${image}" onerror="this.onerror=null;this.src='{{ asset('assets/admin/img/default-user.png') }}';">
                                </span>
                                <div class="media-body noti-item-body">
                                    <p class="noti-details noti-item-msg">
                                       <span class="noti-title">${message}</span>${name && name !== 'Unknown' ? ' <span class="noti-name">' + name + '</span>' : ''}
                                    </p>
                                    <p class="noti-time"><span class="notification-time">${timestamp}</span></p>
                                </div>
                            </div>
                        </a>
                    </li>
                `;

                $notificationList.append(listItem);
            });

            if (!$notificationList.children().length) {
                $notificationList.append(`
                    <li class="notification-empty">
                        <div class="noti-empty-state">
                            <span class="noti-empty-icon"><i class="fe fe-bell"></i></span>
                            <p class="noti-empty-text">No new notifications</p>
                        </div>
                    </li>
                `);
            }

            $notificationCount.text(count);
        });
    }

    function markAllSeen() {
    const userId = '{{ Auth::user()->id }}';
    const notificationsRef = firebase.database().ref('Notification/' + userId);

    notificationsRef.once('value', function(snapshot) {
        snapshot.forEach(function(childSnapshot) {
            const key = childSnapshot.key;
            notificationsRef.child(key).update({ seen: true });
        });
    });
}


    function markSeen(userId, key) {
        firebase.database().ref('Notification/' + userId + '/' + key).update({
            seen: true
        });
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('en-US', {
            year: 'numeric', month: 'short', day: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    }

    function clear_all() {
        const userId = '{{ Auth::user()->id }}';
        $.ajax({
            url: "{{ route('clear_all') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "post",
            complete: function () {
                if (typeof firebase !== 'undefined' && userId) {
                    firebase.database().ref('Notification/' + userId).remove();
                }
                $('#notification-list').empty();
                $('#notification-list').append(`
                    <li class="notification-empty">
                        <div class="noti-empty-state">
                            <span class="noti-empty-icon"><i class="fe fe-bell"></i></span>
                            <p class="noti-empty-text">No new notifications</p>
                        </div>
                    </li>
                `);
                $('#notification-count').text('0');
            }
        });
    }
</script>

<!-- 
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const userId = {{ Auth::id() }};
        const channelName = `private-App.Models.User.${userId}`;
        console.log(channelName);

        // Ask for notification permission
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }

        const pusher = new Pusher('005c0fcc8772ee706435', {
            cluster: 'ap2',
            encrypted: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });

        console.log(pusher,'pusher');
        

        const channel = pusher.subscribe(channelName);
        channel.bind_global((eventName, data) => {
            console.log('Global event received:', eventName, data);
            });


            pusher.trigger("my-channel", "my-event", { message: "hello world" })
            ;
        channel.bind('Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', function (data) {
            console.log('Notification received:', data);

            if (Notification.permission === "granted") {
                new Notification(data.title, {
                    body: data.body,
                    icon: '/icon.png'
                });
            }
        });
    });
</script> -->

<!-- /Header -->
