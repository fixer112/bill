import {
    key,
    pubKey
} from "./key.js";
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
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/firebase-messaging-sw.js')
        .then(function(registration) {
            console.log('Registration successful, scope is:', registration.scope);
            messaging.useServiceWorker(registration);


        }).catch(function(err) {
            console.log('Service worker registration failed, error:', err);
        });
}