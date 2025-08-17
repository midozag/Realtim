import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

console.log('Echo config:', {
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    scheme: import.meta.env.VITE_REVERB_SCHEME
});

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY || 'z6jspkek2dzh9a3vnlig',
    wsHost: import.meta.env.VITE_REVERB_HOST || 'localhost',
    wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT || 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('✅ Connected to Reverb');
});

window.Echo.connector.pusher.connection.bind('disconnected', function() {
    console.log('❌ Disconnected from Reverb');
});

window.Echo.connector.pusher.connection.bind('error', function(err) {
    console.log('🚨 Reverb connection error:', err);
    console.log('Error details:', JSON.stringify(err, null, 2));
});

// Log the actual WebSocket URL being used
console.log('WebSocket URL:', `ws://localhost:8080/app/251288?protocol=7&client=js&version=8.4.0-rc2&flash=false`);

// Test direct WebSocket connection with auth
const testWs = new WebSocket('ws://localhost:8080/app/z6jspkek2dzh9a3vnlig?protocol=7&client=js&version=8.4.0-rc2&flash=false');
testWs.onopen = () => console.log('✅ Direct WebSocket connected');
testWs.onerror = (err) => console.log('❌ Direct WebSocket error:', err);
testWs.onclose = (event) => console.log('❌ Direct WebSocket closed:', event.code, event.reason);
testWs.onmessage = (event) => console.log('📨 WebSocket message:', event.data);