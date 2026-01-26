const WebSocket = require('ws');
const express = require('express');

const app = express();
app.use(express.json());

const wss = new WebSocket.Server({
  port: 3002,
  host: '0.0.0.0'
});

console.log('✅ WebSocket server running on port 3002');

/**
 * =========================
 * BROADCAST HELPER
 * =========================
 */
function broadcast(payload) {
  const message = JSON.stringify(payload);

  wss.clients.forEach(client => {
    if (client.readyState === WebSocket.OPEN) {
      client.send(message);
    }
  });
}

/**
 * =========================
 * WS CONNECTION
 * =========================
 */
wss.on('connection', (ws, req) => {
  ws.send(JSON.stringify({
    type: 'connected',
    message: 'WS connected'
  }));
});

/**
 * =========================
 * HTTP ENDPOINT (FROM CI4)
 * =========================
 */
app.post('/emit', (req, res) => {
  const payload = req.body;

  if (!payload || !payload.type) {
    return res.status(400).json({ message: 'Invalid payload' });
  }

  broadcast(payload);

  return res.json({ success: true });
});

/**
 * HTTP server (PORT 3003)
 */
app.listen(3003, () => {
  console.log('🚀 WS HTTP bridge running on port 3003');
});
