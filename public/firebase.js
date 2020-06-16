import {
    key
} from "./key.js";

console.log('key', key());



var firebaseConfig = {
    apiKey: key(),
    authDomain: "moniwallet.firebaseapp.com",
    databaseURL: "https://moniwallet.firebaseio.com",
    projectId: "moniwallet",
    storageBucket: "moniwallet.appspot.com",
    messagingSenderId: "190032867274",
    appId: "1:190032867274:web:b7437e552b873f5760e933",
    measurementId: "G-32HS8RPHQN"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
firebase.analytics();
const messaging = firebase.messaging();
messaging
    .requestPermission()
    .then(function() {
        console.log("Permission Granted");
        messaging.usePublicVapidKey("BFuDoKbZSsvRyezIBJ5xbDkOIe7MAfHaFXmcbn1QKVS7VQKwT8MNLIm82GNBNmJ1IHosmDShdek4BVPPc5aVSVo");
        messaging.getToken().then((currentToken) => {
            if (currentToken) {

                console.log('current token', currentToken);
                localStorage.setItem('token', currentToken);
                subscribe([currentToken], 'test');
                /* $.post("/", {
                    token: currentToken
                }, function(result) {
                    $("span").html(result);
                }); */

            } else {

                console.log('No Instance ID token available. Request permission to generate one.');

            }
        }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);

        });
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
                .then(function(registration) {
                    console.log('Registration successful, scope is:', registration.scope);
                }).catch(function(err) {
                    console.log('Service worker registration failed, error:', err);
                });
        }
    });


messaging.onTokenRefresh(() => {
    messaging.getToken().then((refreshedToken) => {
        console.log('Token refreshed', refreshedToken);
        localStorage.setItem('token', refreshedToken);
        // ...
    }).catch((err) => {
        console.log('Unable to retrieve refreshed token ', err);
    });
});

messaging.setBackgroundMessageHandler(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here
    const notificationTitle = 'Background Message Title';
    const notificationOptions = {
        body: 'Background Message body.',
        icon: '/firebase-logo.png'
    };

    return self.registration.showNotification(notificationTitle,
        notificationOptions);
});

function subscribe(tokens, topic) {
    messaging.subscribeToTopic(tokens, topic)
        .then(function(response) {
            // See the MessagingTopicManagementResponse reference documentation
            // for the contents of response.
            console.log('Successfully subscribed to topic:', response);
        })
        .catch(function(error) {
            console.log('Error subscribing to topic:', error);
        });

}