<template>
    <div class="flex flex-col items-center justify-center h-[400px] bg-white rounded-lg shadow-md mt-20">
        <h2 class="text-3xl mt-6 font-bold text-indigo-600 mb-2">Авторизация</h2>
        <input type="text" placeholder="email" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-[270px] mt-4" />
        <input type="password" placeholder="password" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-[270px] mt-4" />
        <button 
            type="submit" 
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2 w-[270px] justify-center mt-4 mb-9"
        >
            Войти
        </button>
    </div>
</template>

<script setup>

</script>

<style scoped>

</style>