export default defineNuxtPlugin(() => {
    console.log('Plugin loaded');

    const $api = $fetch.create({
        baseURL: 'http://localhost/api',
    });

    $api('/test').then((response) => {
        console.log(response);
    })
})