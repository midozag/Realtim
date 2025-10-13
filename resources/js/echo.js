import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const reverbConfig = {
    key: import.meta.env.VITE_REVERB_APP_KEY || 'z6jspkek2dzh9a3vnlig',
    wsHost: import.meta.env.VITE_REVERB_HOST || 'localhost',
    wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
    scheme: import.meta.env.VITE_REVERB_SCHEME || 'http'
};

console.log('Echo config:', reverbConfig);

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: reverbConfig.key,
    wsHost: reverbConfig.wsHost,
    wsPort: reverbConfig.wsPort,
    wssPort: reverbConfig.wsPort,
    forceTLS: false,
    enabledTransports: ['ws'],
    // Add these for better debugging
    enableLogging: true,
    logLevel: 'debug'
});

// Connection event listeners
window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('✅ Connected to Reverb WebSocket');
    console.log('Connection state:', window.Echo.connector.pusher.connection.state);
});

window.Echo.connector.pusher.connection.bind('connecting', function() {
    console.log('🔄 Connecting to Reverb...');
});

window.Echo.connector.pusher.connection.bind('disconnected', function() {
    console.log('❌ Disconnected from Reverb');
});

window.Echo.connector.pusher.connection.bind('error', function(err) {
    console.log('🚨 Reverb connection error:', err);
    console.log('Error details:', JSON.stringify(err, null, 2));
    console.log('Error type:', err.type);
    console.log('Error data:', err.data);
});

window.Echo.connector.pusher.connection.bind('unavailable', function() {
    console.log('❌ Reverb connection unavailable');
});

// Test basic connectivity
const testConnection = () => {
    const wsUrl = `ws://${reverbConfig.wsHost}:${reverbConfig.wsPort}/app/${reverbConfig.key}?protocol=7&client=js&version=8.4.0-rc2&flash=false`;
    console.log('Testing WebSocket URL:', wsUrl);
    
    const testWs = new WebSocket(wsUrl);
    
    testWs.onopen = (event) => {
        console.log('✅ Direct WebSocket connected successfully');
        console.log('WebSocket readyState:', testWs.readyState);
        testWs.close();
    };
    
    testWs.onerror = (err) => {
        console.log('❌ Direct WebSocket connection failed:', err);
        console.log('This indicates a connectivity issue to Reverb');
    };
    
    testWs.onclose = (event) => {
        console.log('🔒 WebSocket closed:', {
            code: event.code,
            reason: event.reason,
            wasClean: event.wasClean
        });
    };
};

// Run connection test after a short delay
setTimeout(testConnection, 1000);