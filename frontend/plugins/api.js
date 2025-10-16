export default defineNuxtPlugin(() => {
    const $api = $fetch.create({
        baseURL: 'http://localhost/api',
        credentials: 'include'
    });

    $api('/profile').then(res => {
        console.log(res);
        localStorage.setItem('tasks', res.user.tasks);
        localStorage.setItem('roleUser', res.user.roles[0]);
    }).catch(err => {
        if (err.status === 401) {
            $api('/token/refresh').then(() => {
                console.log('Token обновлен');
            }).catch(err => {
                console.log(err.data.message);
            })
        }
    });
    
    return {
        provide: {
            api: $api
        }
    }
})
