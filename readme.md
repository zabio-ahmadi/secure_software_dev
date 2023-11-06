# a simple twitter like application

this is a small web application build on pure **php** that allows you to create and share posts, follow other users, add users as friends, make audio and video calls, and send messages just like Twitter!

## Features

- **User Registration and Authentication**: Create an account, log in, and manage your profile.

- **Post Creation**: Share your thoughts, updates, and photos with your followers.

- **Following**: Follow other users to see their posts in your feed.

- **Friendship**: Add users as friends to establish a closer connection.

- **Audio Calls**: Make audio calls with your friends directly from the platform.

- **Video Calls**: Have face-to-face video calls with your friends.

- **Direct Messaging**: Send private messages to your friends and followers.

## Getting Started

1. Clone this repository:

   ```bash
   $ git clone https://github.com/zabio-ahmadi/secure_software_dev.git
   ```

2. Change to the project directory:

   ```bash
   $ cd secure_software-dev
   ```

3. modifiy the prod.env and put your own **api keys**:

```env
DB_HOST=mysql
DB_USER=root
DB_PASSWORD=your_data_base_password
DB_NAME=social_network
SMTP_HOST=smtp.gmail.com
SMTP_USER_MAIL=your_email_address
SMTP_USER_PASSWORD=your_app_password #created in google two step verification
SMTP_PORT=465
SMTP_PROTOCOL=ssl
SMTP_SENDER_EMAIL_ADDRESS=webmaster@yourmail.ch
SESSION_DURATION=3600 # duration of the session
ecryption_key=your_secret_key # your secret for a jwt like encryption method
```

4. run the docker command :

   ```bash
   $  docker compose up -d
   ```

5. Open your web browser and visit [http://localhost:80](http://localhost:) to use secure dev app.
