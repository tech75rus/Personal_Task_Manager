export const useTasks = () => {
    const { $api } = useNuxtApp();

    const saveTask = async (tasks) => {
        await $api('save-task', {
            method: 'POST',
            body: {
                taks: tasks
            }
        })
    }

    return { saveTask }
}
