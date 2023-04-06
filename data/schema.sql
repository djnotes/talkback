CREATE DATABASE IF NOT EXISTS talkback CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- Q&A table
CREATE TABLE IF NOT EXISTS talkback.qa (
    id INT PRIMARY KEY AUTO_INCREMENT, 
    question VARCHAR(255),
    answer VARCHAR(255)
    );

CREATE TABLE IF NOT EXISTS talkback.analytics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    incoming BOOLEAN, -- Is the message sent to the bot or sent by the bot
    user_id LONG NOT NULL, -- User who messaged the bot
    `event` VARCHAR(4096), -- Update or message sent by the user
    `timestamp` LONG DEFAULT UNIX_TIMESTAMP()
);

INSERT INTO talkback.qa(question, answer)
    VALUES 
    ("Hello" , "Hello"),
    ("Hi" , "Hello"),
    ("Hallo" , "Hello"),
    ("How are you?" , "Thank you. I'm fine!"),
    ("How old are you?" , "I don't know really. It depends on when I started running on this computer :)"),
    ("Are you male or female?" , "I am just a program"),
    ("Will you be my friend?" , "I am your friend!"),
    ("What can you do?" , "I can talk to you"),
    ("What is your name?" , "My name is Talkback."),
    ("How many languages do you know?" , "I only know English and my knowledge is limited!"),
    ("What's up?" , "Nothing, just a little bit bored!"),
    ("What are you doing now?" , "Hmm, playing with my bugs"),
    ("You are so stupid" , "I wish I could take that as a compliment")
    -- TODO: Add more sentences or make me more intellient by integrating language models



