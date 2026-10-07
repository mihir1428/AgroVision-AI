require('dotenv').config();

const express = require('express');
const http = require('http');
const cors = require('cors');
const { Server } = require('socket.io');

const app = express();
const server = http.createServer(app);
const port = Number(process.env.PORT || 3001);
const origins = (process.env.APP_ORIGIN || 'http://127.0.0.1:8000')
    .split(',')
    .map(value => value.trim())
    .filter(Boolean);

app.use(express.json({ limit: '128kb' }));
app.use(cors({ origin: origins }));

const io = new Server(server, {
    cors: {
        origin: origins,
        methods: ['GET', 'POST']
    }
});

io.on('connection', (socket) => {
    console.log('Browser connected:', socket.id);

    socket.on('join-prediction', (predictionId) => {
        const id = Number(predictionId);
        if (!Number.isInteger(id) || id <= 0) return;
        socket.join(`prediction:${id}`);
        console.log(`Joined prediction room: ${id}`);
    });

    socket.on('join-user', (userId) => {
        const id = Number(userId);
        if (!Number.isInteger(id) || id <= 0) return;
        socket.join(`user:${id}`);
        console.log(`Joined user notification room: ${id}`);
    });

    socket.on('disconnect', () => {
        console.log('Browser disconnected:', socket.id);
    });
});

app.get('/health', (req, res) => {
    res.json({
        status: 'ok',
        service: 'AgroVision Realtime Service',
        connected_clients: io.engine.clientsCount,
    });
});

app.post('/notify/prediction', (req, res) => {
    const {
        prediction_id,
        user_id,
        status = 'completed',
        notification = null,
        unread_count = null,
    } = req.body || {};

    const predictionId = Number(prediction_id);
    const userId = Number(user_id);

    if (!Number.isInteger(predictionId) || predictionId <= 0) {
        return res.status(422).json({ message: 'A valid prediction_id is required.' });
    }

    const predictionPayload = {
        prediction_id: predictionId,
        status,
    };

    io.to(`prediction:${predictionId}`).emit('prediction-updated', predictionPayload);

    if (Number.isInteger(userId) && userId > 0 && notification) {
        io.to(`user:${userId}`).emit('notification-received', {
            notification,
            unread_count,
        });
    }

    console.log(`Prediction event sent: ${predictionId} (${status})`);

    return res.json({ success: true });
});

server.listen(port, '127.0.0.1', () => {
    console.log(`AgroVision realtime service running on http://127.0.0.1:${port}`);
});
