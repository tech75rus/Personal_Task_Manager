export default defineNuxtPlugin(() => {
    const $api = $fetch.create({
        baseURL: 'http://localhost/api',
        credentials: 'include'
    });
    
    if(process.client) {
        localStorage.setItem('roleUser', 'Guest');
    }

    $api('/profile').then(res => {
        const task = res.user.tasks;
        task.taks = JSON.parse(task.taks);
        localStorage.setItem('tasks', JSON.stringify(task.taks));
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
