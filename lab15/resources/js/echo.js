import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

/* Echo подключается к Pusher и слушает каналы вещания.
   Ключи берутся из .env (VITE_PUSHER_...) в момент сборки: после их изменения нужен npm run build. */
window.Pusher = Pusher;

if (import.meta.env.VITE_PUSHER_APP_KEY) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
    });
} else {
    console.warn('PUSHER_APP_KEY не задан в .env — push-уведомления отключены.');
}
