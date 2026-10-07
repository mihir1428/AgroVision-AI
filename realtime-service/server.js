const express = require('express');
const http = require('http');
const cors = require('cors');
const { Server } = require('socket.io');

const app = express();
const server = http.createServer(app);

app.use(express.json());

app.use(cors({
    origin: 'http://127.0.0.1:8000'
}));

const io = new Server(server, {
    cors: {
        origin: 'http://127.0.0.1:8000',
        methods: ['GET', 'POST']
    }
});

io.on('connection', (socket) => {
    console.log('Browser connected:', socket.id);

    socket.on('join-prediction', (predictionId) => {
        socket.join(`prediction:${predictionId}`);

        console.log(
            `Joined prediction room: ${predictionId}`
        );
    });

    socket.on('disconnect', () => {
        console.log('Browser disconnected:', socket.id);
    });
});

app.get('/health', (req, res) => {
    res.json({
        status: 'ok',
        service: 'AgroVision Realtime Service'
    });
});

app.post('/notify/prediction', (req, res) => {
    const { prediction_id, status } = req.body;

    if (!prediction_id) {
        return res.status(422).json({
            message: 'prediction_id is required'
        });
    }

    io.to(`prediction:${prediction_id}`).emit(
        'prediction-updated',
        {
            prediction_id,
            status: status || 'completed'
        }
    );

    console.log(
        `Notification sent for prediction ${prediction_id}`
    );

    res.json({
        success: true
    });
});

const PORT = 3001;

server.listen(PORT, '127.0.0.1', () => {
    console.log(
        `AgroVision realtime service running on http://127.0.0.1:${PORT}`
    );
});