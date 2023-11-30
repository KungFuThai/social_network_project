
import express from 'express';
import { createServer } from 'http';
import { Server } from 'socket.io';
import Redis from 'ioredis';

const app = express();
const httpServer = createServer(app);

const io = new Server(httpServer, {
    cors: {
        origin: '*'
    }
});

const redis = new Redis(1000);
const users = [];

redis.subscribe("private-channel", function () {
    console.log('Subscribed to private channel')
});

redis.on("message", function (channel,message){
    const eventData = JSON.parse(message)
    console.log(channel);
    if(channel === "private-channel"){
        let data = eventData.data.message;
        let receiver_id = data.receiver_id;
        let event = eventData.event;

        io.to(`${users[receiver_id]}`).emit(channel + ':' + event, data);
    }
    console.log(message);
});

httpServer.listen(3000, () => {
    console.log('Server is running');
});

io.on('connection', (socket) => {
    socket.on('user_connected', (userId) => {
        users[userId] = socket.id;
        io.emit('updateUserStatus', users);
        console.log('user_connected ' + userId);
    });

    socket.on('disconnect', function () {
        const i = users.indexOf(socket.id);
        users.splice(i, 1, 0);
        io.emit('updateUserStatus', users);
    });
});