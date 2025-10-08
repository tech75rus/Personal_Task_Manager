export default defineNuxtPlugin(() => {
    const $api = $fetch.create({
        baseURL: 'http://localhost/api',
    });

    $api('/test').then((response) => {
        console.log(response);
    })

    return {
        provide: {
            api: $api
        }
    }
})