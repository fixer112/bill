var firebaseConfig = {
    apiKey: "AIzaSyByXS9ACahY2QKmlnXfeFAXffWjMXn0tgM",
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
        console.log("Have permission");
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
                .then(function(registration) {
                    console.log('Registration successful, scope is:', registration.scope);
                }).catch(function(err) {
                    console.log('Service worker registration failed, error:', err);
                });
        }
    });

/* messaging.usePublicVapidKey("BFuDoKbZSsvRyezIBJ5xbDkOIe7MAfHaFXmcbn1QKVS7VQKwT8MNLIm82GNBNmJ1IHosmDShdek4BVPPc5aVSVo");
messaging.getToken().then((currentToken) => {
    if (currentToken) {
        //sendTokenToServer(currentToken);
        //updateUIForPushEnabled(currentToken);
        console.log(currentToken);
    } else {
        // Show permission request.
        console.log('No Instance ID token available. Request permission to generate one.');
        // Show permission UI.
        //updateUIForPushPermissionRequired();
        //setTokenSentToServer(false);
    }
}).catch((err) => {
    console.log('An error occurred while retrieving token. ', err);
    //showToken('Error retrieving Instance ID token. ', err);
    //setTokenSentToServer(false);
}); */