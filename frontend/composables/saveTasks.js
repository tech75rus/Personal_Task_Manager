export const useTasks = () => {
    const { $api } = useNuxtApp();

    const getTasks = async () => {
        await $api('/test').then(res => {
            console.log(res);
        }).catch(err => {
            console.log(err);
        });
    }

    return { getTasks }
}
