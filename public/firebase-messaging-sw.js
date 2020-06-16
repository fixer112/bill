importScripts('https://www.gstatic.com/firebasejs/7.15.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.15.0/firebase-messaging.js');
importScripts('https://www.gstatic.com/firebasejs/7.15.1/firebase-analytics.js');

import {
    key,
    pubKey
} from "./key.js";

console.log('key', key());

function notifyMe(body, title) {
    if (Notification.permission !== 'granted') {
        console.log('No permission');
        Notification.requestPermission();
    } else {
        var notification = new Notification(title, {
            icon: 'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png',
            body: body,
        });
        /* notification.onclick = function() {
            window.open('http://stackoverflow.com/a/13328397/1269037');
        }; */
    }
}

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
        messaging.usePublicVapidKey(pubKey());
        messaging.getToken().then((currentToken) => {
            if (currentToken) {

                console.log('current token', currentToken);
                localStorage.setItem('token', currentToken);

                updateToken(currentToken);

                //subscribe([currentToken], 'test');
                /* $.post("/", {
                    token: currentToken
                }, function(result) {
                    console.log(result);
                }); */

            } else {

                console.log('No Instance ID token available. Request permission to generate one.');

            }
        }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);

        });

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



messaging.onMessage((payload) => {
    console.log('Message received. ', payload);
    //notifyMe(body, title);
    // ...
});



/* function subscribe(tokens, topic) {
    messaging.subscribeToTopic(tokens, topic)
        .then(function(response) {
            // See the MessagingTopicManagementResponse reference documentation
            // for the contents of response.
            console.log('Successfully subscribed to topic:', response);
        })
        .catch(function(error) {
            console.log('Error subscribing to topic:', error);
        });

} */

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