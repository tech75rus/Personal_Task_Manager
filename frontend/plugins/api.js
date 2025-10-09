export default defineNuxtPlugin(() => {
    const $api = $fetch.create({
        baseURL: 'http://localhost/api',
        credentials: 'include'
    });

    $api('/profile').then((response) => {
        console.log(response);
    })

    return {
        provide: {
            api: $api
        }
    }
})