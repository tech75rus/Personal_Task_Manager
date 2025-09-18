<template>
    <div class="bg-gray-50 min-h-screen font-sans">
            <div class="container mx-auto px-4 py-8 max-w-3xl">
        <!-- Заголовок -->
        <header class="text-center mb-8">
            <h1 class="text-4xl font-bold text-indigo-600 mb-2">Умный менеджер задач</h1>
            <p class="text-gray-600">Организуйте свою работу эффективно</p>
            
        </header>

        <!-- Форма добавления задачи -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form id="task-form" class="flex gap-2">
                <input 
                    type="text" 
                    id="task-input" 
                    placeholder="Добавьте новую задачу..." 
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required
                >
                <button 
                    type="submit" 
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2"
                >
                    <i class="fas fa-plus"></i> Добавить
                </button>
            </form>
        </div>

        <!-- Фильтры -->
        <div class="flex justify-center mb-6">
            <div class="bg-white rounded-lg shadow-md p-2 inline-flex">
                <button 
                    data-filter="all" 
                    class="filter-btn px-4 py-2 rounded-md hover:bg-gray-100 transition active-filter bg-indigo-100 text-indigo-600"
                >
                    Все
                </button>
                <button 
                    data-filter="active" 
                    class="filter-btn px-4 py-2 rounded-md hover:bg-gray-100 transition"
                >
                    Активные
                </button>
                <button 
                    data-filter="completed" 
                    class="filter-btn px-4 py-2 rounded-md hover:bg-gray-100 transition"
                >
                    Завершенные
                </button>
            </div>
        </div>

        <!-- Статистика -->
        <div class="flex justify-between items-center mb-4 px-2">
            <div class="text-sm text-gray-500">
                <span id="tasks-count">0</span> задач(и)
            </div>
            <button 
                id="clear-completed" 
                class="text-sm text-gray-500 hover:text-indigo-600 transition"
            >
                Очистить завершенные
            </button>
        </div>

        <!-- Список задач -->
        <div id="task-list" class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Задачи будут добавляться здесь -->
            <div id="empty-state" class="p-8 text-center">
                <i class="fas fa-tasks text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-500">Нет задач</h3>
                <p class="text-gray-400">Добавьте свою первую задачу</p>
            </div>
        </div>

        <!-- Уведомление -->
        <div id="notification" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg hidden">
            <span id="notification-message"></span>
        </div>
    </div>
    </div>
</template>

<script setup>
onMounted(() => {
    // Элементы DOM
    const taskForm = document.getElementById('task-form');
    const taskInput = document.getElementById('task-input');
    const taskList = document.getElementById('task-list');
    const emptyState = document.getElementById('empty-state');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const clearCompletedBtn = document.getElementById('clear-completed');
    const tasksCount = document.getElementById('tasks-count');
    const notification = document.getElementById('notification');
    
    let tasks = JSON.parse(localStorage.getItem('tasks')) || [];
    let currentFilter = 'all';

    // Инициализация
    renderTasks();
    updateCounter();

    // Добавление задачи
    taskForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const taskText = taskInput.value.trim();
        if (taskText) {
            const newTask = {
                id: Date.now(),
                text: taskText,
                completed: false,
                createdAt: new Date().toISOString()
            };
            
            tasks.push(newTask);
            saveTasks();
            renderTasks();
            updateCounter();
            showNotification('Задача добавлена!');
            
            taskInput.value = '';
            taskInput.focus();
        }
    });

    // Фильтрация задач
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentFilter = this.dataset.filter;
            
            // Обновляем активную кнопку
            filterButtons.forEach(btn => btn.classList.remove('active-filter', 'bg-indigo-100', 'text-indigo-600'));
            this.classList.add('active-filter', 'bg-indigo-100', 'text-indigo-600');
            
            renderTasks();
        });
    });

    // Очистка завершенных задач
    clearCompletedBtn.addEventListener('click', function() {
        tasks = tasks.filter(task => !task.completed);
        saveTasks();
        renderTasks();
        updateCounter();
        showNotification('Завершенные задачи удалены');
    });

    // Функция отрисовки задач
    function renderTasks() {
        // Фильтрация задач
        let filteredTasks = [];
        if (currentFilter === 'all') {
            filteredTasks = tasks;
        } else if (currentFilter === 'active') {
            filteredTasks = tasks.filter(task => !task.completed);
        } else if (currentFilter === 'completed') {
            filteredTasks = tasks.filter(task => task.completed);
        }
        
        // Очистка списка
        taskList.innerHTML = '';
        
        // Если нет задач - показать пустое состояние
        if (filteredTasks.length === 0) {
            taskList.appendChild(emptyState);
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
        // Добавление задач
        filteredTasks.forEach(task => {
            const taskElement = createTaskElement(task);
            taskList.appendChild(taskElement);
        });
    }

    // Создание элемента задачи
    function createTaskElement(task) {
        const taskElement = document.createElement('div');
        taskElement.className = 'task-item border-b border-gray-100 last:border-0 hover:bg-gray-50 transition fade-in cursor-move';
        taskElement.dataset.id = task.id;
        taskElement.draggable = true;
        
        taskElement.innerHTML = `
            <div class="flex items-center p-4">
                <label class="flex items-center cursor-pointer flex-1">
                    <input 
                        type="checkbox" 
                        class="checkbox h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500 mr-3 cursor-pointer" 
                        ${task.completed ? 'checked' : ''}
                    >
                    <span class="task-text ${task.completed ? 'line-through text-gray-400' : 'text-gray-800'}">${task.text}</span>
                </label>
                <div class="task-actions transition flex gap-2">
                    <button class="edit-btn p-1 text-gray-400 hover:text-indigo-600">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button class="delete-btn p-1 text-gray-400 hover:text-red-600">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        // Обработчики событий для элемента
        const checkbox = taskElement.querySelector('.checkbox');
        const editBtn = taskElement.querySelector('.edit-btn');
        const deleteBtn = taskElement.querySelector('.delete-btn');
        
        // Drag and drop handlers
        taskElement.addEventListener('dragstart', handleDragStart);
        taskElement.addEventListener('dragover', handleDragOver);
        taskElement.addEventListener('dragleave', handleDragLeave);
        taskElement.addEventListener('drop', handleDrop);
        taskElement.addEventListener('dragend', handleDragEnd);
        
        checkbox.addEventListener('change', function() {
            task.completed = this.checked;
            saveTasks();
            updateCounter();
            
            const taskText = taskElement.querySelector('.task-text');
            if (task.completed) {
                taskText.classList.add('line-through', 'text-gray-400');
                showNotification('Задача завершена!');
            } else {
                taskText.classList.remove('line-through', 'text-gray-400');
            }
            
            if (currentFilter !== 'all') {
                setTimeout(() => {
                    renderTasks();
                }, 300);
            }
        });
            
        editBtn.addEventListener('click', function() {
            const newText = prompt('Редактировать задачу:', task.text);
            if (newText && newText.trim() !== '') {
                task.text = newText.trim();
                saveTasks();
                renderTasks();
                showNotification('Задача обновлена');
            }
        });
        
        deleteBtn.addEventListener('click', function() {
            if (confirm('Удалить эту задачу?')) {
                tasks = tasks.filter(t => t.id !== task.id);
                saveTasks();
                renderTasks();
                updateCounter();
                showNotification('Задача удалена');
            }
        });
        
        return taskElement;
    }

    // Сохранение задач в localStorage
    function saveTasks() {
        localStorage.setItem('tasks', JSON.stringify(tasks));
    }

    // Обновление счетчика задач
    function updateCounter() {
        const activeTasks = tasks.filter(task => !task.completed).length;
        const completedTasks = tasks.filter(task => task.completed).length;
        
        if (currentFilter === 'all') {
            tasksCount.textContent = `${tasks.length} (${activeTasks} активных, ${completedTasks} завершенных)`;
        } else if (currentFilter === 'active') {
            tasksCount.textContent = `${activeTasks} активных`;
        } else {
            tasksCount.textContent = `${completedTasks} завершенных`;
        }
    }

    // Показать уведомление
    function showNotification(message) {
        const notificationMessage = document.getElementById('notification-message');
        notificationMessage.textContent = message;
        
        notification.classList.remove('hidden');
        notification.classList.add('animate-bounce');
        
        setTimeout(() => {
            notification.classList.remove('animate-bounce');
            notification.classList.add('hidden');
        }, 3000);
    }

    // Drag and drop functions
    let draggedItem = null;

    function handleDragStart(e) {
        draggedItem = this;
        e.dataTransfer.effectAllowed = 'move';
        this.classList.add('opacity-50', 'border-dashed', 'border-2', 'border-indigo-300');
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        this.classList.add('bg-indigo-50');
    }

    function handleDragLeave() {
        this.classList.remove('bg-indigo-50');
    }

    function handleDrop(e) {
        e.preventDefault();
        this.classList.remove('bg-indigo-50');
        
        if (draggedItem !== this) {
            const draggedId = parseInt(draggedItem.dataset.id);
            const targetId = parseInt(this.dataset.id);
            
            const draggedIndex = tasks.findIndex(task => task.id === draggedId);
            const targetIndex = tasks.findIndex(task => task.id === targetId);
            
            // Reorder tasks array
            const [removed] = tasks.splice(draggedIndex, 1);
            tasks.splice(targetIndex, 0, removed);
            
            saveTasks();
            renderTasks();
            showNotification('Порядок задач обновлен');
        }
    }

    function handleDragEnd() {
        this.classList.remove('opacity-50', 'border-dashed', 'border-2', 'border-indigo-300');
        draggedItem = null;
    }
});
</script>

<style scoped>
.task-item:hover .task-actions {
    opacity: 1;
}
.checkbox:checked + .task-text {
    text-decoration: line-through;
    color: #9ca3af;
}
.fade-in {
    animation: fadeIn 0.3s ease-in-out;
}
.task-item.dragging {
    opacity: 0.5;
    transform: scale(0.95);
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

</style>