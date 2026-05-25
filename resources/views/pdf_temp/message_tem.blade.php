<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>New Message</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 16px;
      line-height: 1.5;
      color: #333;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #3498db;
      padding: 20px;
    }
    .header img {
      height: 50px;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #f5f5f5;
    }
    h1 {
      font-size: 24px;
      margin-top: 0;
      margin-bottom: 20px;
    }
    p {
      margin-top: 0;
      margin-bottom: 20px;
    }
    button {
      display: inline-block;
      padding: 10px 20px;
      font-size: 16px;
      font-weight: bold;
      text-align: center;
      color: #fff;
      background-color: #3498db;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="header">
    <img src="https://caco.do/assets/cacofront/images/logo.png" alt="Logo">
       
  </div>
  <div class="container">
    <p>Someone has sent you a new message:</p>
    <p><strong> First Name:</strong> {{@$info['name']}}</p>
    <p><strong>Message:</strong></p>
    <p><strong>{{@$info['message']}}</strong></p>
    <button type="button"><a href="https://caco.do/chat/{{@$info['id']}}">Reply</a></button>
  </div>
</body>
</html>
