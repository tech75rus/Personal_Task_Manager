<template>
    <div class="relative flex flex-col items-center justify-center h-[400px] bg-white rounded-lg shadow-md mt-20">
        <h2 class="text-3xl mt-6 font-bold text-indigo-600 mb-2">Регистрация</h2>
        <input v-model="form.email" type="text" placeholder="email" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-[270px] mt-4" />
        <input v-model="form.password" type="password" placeholder="password" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-[270px] mt-4" />
        <button 
            @click="register"
            type="submit" 
            :disabled="loading"
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2 w-[270px] justify-center mt-4 mb-9 disabled:bg-gray-400 disabled:cursor-not-allowed"
        >
            {{ loading ? 'Регистрация...' : 'Зарегистрироваться' }}
        </button>
        
        <!-- Уведомление об успехе -->
        <div
            v-if="showSuccessMessage"
            :class="{ 'bg-green-500': success, 'bg-red-700': hasError }"
            class="absolute bottom-4 text-white px-4 py-2 rounded-lg shadow-lg transition-all duration-300"
        >
            <span>{{ successMessage }}</span>

        </div>
    </div>
</template>

<script setup>
const form = reactive({
    email: '',
    password: ''
});

const loading = ref(false);
const showSuccessMessage = ref(false);
const successMessage = ref('');
const success = ref(false);
const hasError = ref(false);

const { $api } = useNuxtApp();

const register = async () => {
    loading.value = true;
    
    try {
        const response = await $api('/register', {
            method: 'POST',
            body: {
                email: form.email,
                password: form.password
            }
        });

        // Очищаем форму
        form.email = '';
        form.password = '';

        success.value = true;

        // Показываем сообщение об успехе
        successMessage.value = response?.message || 'Пользователь успешно зарегистрирован!';
        showSuccessMessage.value = true;


        // Через 3 секунды делаем редирект на главную
        setTimeout(() => {
            navigateTo('/'); // Редирект на главную страницу
        }, 3000);

    } catch(error) {
        console.log('Ошибка регистрации:', error);
        
        // Показываем сообщение об ошибке
        successMessage.value = error.data?.message || 'Ошибка при регистрации';
        showSuccessMessage.value = true;
        hasError.value = true;

        // Скрываем сообщение об ошибке через 5 секунд
        setTimeout(() => {
            showSuccessMessage.value = false;
        }, 5000);
        
    } finally {
        loading.value = false;
    }
}
</script>