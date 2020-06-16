import {
    key,
    pubKey
} from "./key.js";

console.log('key', pubKey());

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
const messaging = firebase.messaging();
messaging.usePublicVapidKey(pubKey());
messaging
    .requestPermission()
    .then(function() {
        console.log("Permission Granted");
        messaging.getToken().then((currentToken) => {
            if (currentToken) {

                console.log('current token', currentToken);
                localStorage.setItem('token', currentToken);

                updateToken(currentToken);



            } else {

                console.log('No Instance ID token available. Request permission to generate one.');

            }
        }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);

        });

    });

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/firebase-messaging-sw.js')
        .then(function(registration) {
            console.log('Registration successful, scope is:', registration.scope);


        }).catch(function(err) {
            console.log('Service worker registration failed, error:', err);
        });
}

messaging.onTokenRefresh(() => {
    messaging.getToken().then((refreshedToken) => {
        console.log('Token refreshed', refreshedToken);
        localStorage.setItem('token', refreshedToken);
        // ...
    }).catch((err) => {
        console.log('Unable to retrieve refreshed token ', err);
    });
});



messaging.onMessage((payload) => {
    console.log('Message received. ', payload);

});

function updateToken(app_token) {
    var user_id = localStorage.getItem('user_id');
    var user_token = localStorage.getItem('user_token');
    //console.log('user_token', user_token);
    if (user_id && user_token) {
        $.post("/api/user/" + user_id + "/update_token?api_token=" + user_token, {
            app_token: app_token
        }, function(result) {
            console.log(result);
        }).fail(function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        });
    }
}