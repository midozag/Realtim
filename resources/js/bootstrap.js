import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Reverb configuration with fallbacks
const reverbConfig = {
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY || 'z6jspkek2dzh9a3vnlig',
    wsHost: import.meta.env.VITE_REVERB_HOST || 'localhost',
    wsPort: parseInt(import.meta.env.VITE_REVERB_PORT) || 443,
    wssPort: parseInt(import.meta.env.VITE_REVERB_PORT) || 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME || 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
    wsPath: import.meta.env.VITE_REVERB_PATH || '/reverb',
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                window.axios.post('/api/broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name
                })
                .then(response => {
                    callback(false, response.data);
                })
                .catch(error => {
                    callback(true, error);
                });
            }
        };
    },
};

console.log('Echo config:', {
    key: reverbConfig.key,
    wsHost: reverbConfig.wsHost,
    wsPort: reverbConfig.wsPort,
    scheme: import.meta.env.VITE_REVERB_SCHEME
});

window.Echo = new Echo(reverbConfig);
