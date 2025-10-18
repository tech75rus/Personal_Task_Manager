export const useAuth = () => {
    const { $api } = useNuxtApp();

    const logout = async () => {
        try {
            const res = await $api('/logout');
            console.log('Выход выполнен');
            return res;
        } catch (error) {
            console.log(error);
        }
    }

    return { logout };
}