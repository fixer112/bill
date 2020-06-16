if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/firebase-messaging-sw.js')
        .then(function(registration) {
            console.log('Registration successful, scope is:', registration.scope);
            /* messaging.setBackgroundMessageHandler(function(payload) {
                console.log('[firebase-messaging-sw.js] Received background message ', payload);
                // Customize notification here
                //notifyMe(body, title);
            }); */
        }).catch(function(err) {
            console.log('Service worker registration failed, error:', err);
        });
}