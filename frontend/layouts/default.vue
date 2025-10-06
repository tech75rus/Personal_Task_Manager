<!-- app.vue -->
<template>
  <Html>
    <Head>
      <Link 
        rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous"
      />
    </Head>
    <div class="bg-gray-50 min-h-screen font-sans">
      <div class="container mx-auto px-4 py-8 max-w-3xl">
        <NuxtPage />
      </div>
    </div>
  </Html>
</template>

<script setup>
</script>