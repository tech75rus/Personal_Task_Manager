export const useTasks = () => {
    const { $api } = useNuxtApp();

    const apiSaveTask = async (tasks) => {
        await $api('/save-task', {
            method: 'POST',
            body: {
                taks: tasks
            }
        })
    }

    const apiGetTasks = async () => {
        const response = await $api('/get-task', {
            method: 'GET'
        });
        return response;
    }

    return { apiSaveTask, apiGetTasks }
}
