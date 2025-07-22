<script setup>
import { ref, onMounted, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'

// Состояние активного таба
const activeTab = ref(0)

// Состояние форм
const loginForm = ref({
    email: '',
    password: ''
})

const registerForm = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
})

// Состояние ошибок
const loginErrors = ref({})
const registerErrors = ref({})

// Состояние загрузки
const isLoading = ref(false)

// Состояние валидации форм
const isLoginFormValid = ref(false)
const isRegisterFormValid = ref(false)

// Состояние видимости паролей
const showLoginPassword = ref(false)
const showRegisterPassword = ref(false)
const showConfirmPassword = ref(false)

// Обработка query параметра при монтировании
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search)
    if (urlParams.get('registration') !== null) {
        activeTab.value = 1
    }
    
    // Инициализация валидации форм
    validateLoginForm()
    validateRegisterForm()
})

// Функция входа
const handleLogin = async () => {
    if (!isLoginFormValid.value) return
    
    isLoading.value = true
    loginErrors.value = {}
    
    try {
        await router.post('/login', loginForm.value, {
            onError: (errors) => {
                loginErrors.value = errors
            },
            onSuccess: () => {
                router.visit('/dashboard')
            }
        })
    } catch (error) {
        console.error('Login error:', error)
    } finally {
        isLoading.value = false
    }
}

// Функция регистрации
const handleRegister = async () => {
    if (!isRegisterFormValid.value) return
    
    isLoading.value = true
    registerErrors.value = {}
    
    try {
        await router.post('/register', registerForm.value, {
            onError: (errors) => {
                registerErrors.value = errors
            },
            onSuccess: () => {
                router.visit('/dashboard')
            }
        })
    } catch (error) {
        console.error('Register error:', error)
    } finally {
        isLoading.value = false
    }
}

// Очистка ошибок при смене таба
const handleTabChange = (tabIndex) => {
    activeTab.value = tabIndex
    loginErrors.value = {}
    registerErrors.value = {}
}

// Проверка валидности формы входа
const validateLoginForm = () => {
    const { email, password } = loginForm.value
    isLoginFormValid.value = email.trim() !== '' && password.trim() !== '' && password.length >= 6
}

// Проверка валидности формы регистрации
const validateRegisterForm = () => {
    const { name, email, password, password_confirmation } = registerForm.value
    const isNameValid = name.trim() !== '' && name.length >= 2
    const isEmailValid = email.trim() !== '' && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
    const isPasswordValid = password.trim() !== '' && password.length >= 6
    const isPasswordConfirmationValid = password_confirmation.trim() !== '' && password === password_confirmation
    
    isRegisterFormValid.value = isNameValid && isEmailValid && isPasswordValid && isPasswordConfirmationValid
}

// Наблюдатели за изменениями в формах
watch(() => loginForm.value, validateLoginForm, { deep: true })
watch(() => registerForm.value, validateRegisterForm, { deep: true })

// Функция для получения классов таба
const getTabClasses = (selected) => {
    return `w-full rounded-lg py-2.5 text-sm font-medium leading-5 ring-white ring-opacity-60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2 ${
        selected
            ? 'bg-white shadow text-blue-700'
            : 'text-blue-100 hover:bg-white/[0.12] hover:text-white'
    }`
}

// Функция для получения классов поля ввода
const getInputClasses = (hasError = false) => {
    return `block w-full rounded-md border-0 py-1.5 pl-3 pr-3 text-gray-900 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6 ${
        hasError
            ? 'ring-red-300 focus:ring-red-500'
            : 'ring-gray-300 focus:ring-blue-600'
    }`
}
</script>

<template>
    <Head title="Авторизация" />
    
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-blue-100">
                    <span class="text-2xl text-blue-600">👤</span>
                </div>
                <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                    Добро пожаловать
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Войдите в свой аккаунт или создайте новый
                </p>
            </div>
            
            <!-- Простые табы без Headless UI -->
            <div class="flex space-x-1 rounded-xl bg-blue-900/20 p-1">
                <button 
                    @click="handleTabChange(0)"
                    :class="getTabClasses(activeTab === 0)"
                >
                    Вход
                </button>
                <button 
                    @click="handleTabChange(1)"
                    :class="getTabClasses(activeTab === 1)"
                >
                    Регистрация
                </button>
            </div>
            
            <!-- Панели контента -->
            <div class="mt-8">
                <!-- Панель входа -->
                <div v-if="activeTab === 0">
                    <form @submit.prevent="handleLogin" class="space-y-6">
                        <!-- Email поле -->
                        <div>
                            <label for="login-email" class="block text-sm font-medium leading-6 text-gray-900">
                                Email
                            </label>
                            <div class="relative mt-2">
                                <input
                                    id="login-email"
                                    v-model="loginForm.email"
                                    type="email"
                                    :class="getInputClasses(loginErrors.email)"
                                    placeholder="Введите ваш email"
                                    required
                                />
                            </div>
                            <p v-if="loginErrors.email" class="mt-2 text-sm text-red-600">
                                {{ loginErrors.email }}
                            </p>
                        </div>
                        
                        <!-- Пароль поле -->
                        <div>
                            <label for="login-password" class="block text-sm font-medium leading-6 text-gray-900">
                                Пароль
                            </label>
                            <div class="relative mt-2">
                                <input
                                    id="login-password"
                                    v-model="loginForm.password"
                                    :type="showLoginPassword ? 'text' : 'password'"
                                    :class="getInputClasses(loginErrors.password)"
                                    placeholder="Введите ваш пароль"
                                    required
                                />
                                <button
                                    type="button"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3"
                                    @click="showLoginPassword = !showLoginPassword"
                                >
                                    <span class="text-gray-400 hover:text-gray-600">
                                        {{ showLoginPassword ? '🙈' : '👁️' }}
                                    </span>
                                </button>
                            </div>
                            <p v-if="loginErrors.password" class="mt-2 text-sm text-red-600">
                                {{ loginErrors.password }}
                            </p>
                        </div>
                        
                        <!-- Кнопка входа -->
                        <div>
                            <button
                                type="submit"
                                :disabled="!isLoginFormValid || isLoading"
                                class="group relative flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span v-if="isLoading" class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                {{ isLoading ? 'Вход...' : 'Войти' }}
                            </button>
                            <div v-if="!isLoginFormValid && !isLoading" class="mt-2 text-xs text-gray-500 text-center">
                                Заполните все обязательные поля для входа
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Панель регистрации -->
                <div v-if="activeTab === 1">
                    <form @submit.prevent="handleRegister" class="space-y-6">
                        <!-- Имя поле -->
                        <div>
                            <label for="register-name" class="block text-sm font-medium leading-6 text-gray-900">
                                Имя
                            </label>
                            <div class="relative mt-2">
                                <input
                                    id="register-name"
                                    v-model="registerForm.name"
                                    type="text"
                                    :class="getInputClasses(registerErrors.name)"
                                    placeholder="Введите ваше имя"
                                    required
                                />
                            </div>
                            <p v-if="registerErrors.name" class="mt-2 text-sm text-red-600">
                                {{ registerErrors.name }}
                            </p>
                        </div>
                        
                        <!-- Email поле -->
                        <div>
                            <label for="register-email" class="block text-sm font-medium leading-6 text-gray-900">
                                Email
                            </label>
                            <div class="relative mt-2">
                                <input
                                    id="register-email"
                                    v-model="registerForm.email"
                                    type="email"
                                    :class="getInputClasses(registerErrors.email)"
                                    placeholder="Введите ваш email"
                                    required
                                />
                            </div>
                            <p v-if="registerErrors.email" class="mt-2 text-sm text-red-600">
                                {{ registerErrors.email }}
                            </p>
                        </div>
                        
                        <!-- Пароль поле -->
                        <div>
                            <label for="register-password" class="block text-sm font-medium leading-6 text-gray-900">
                                Пароль
                            </label>
                            <div class="relative mt-2">
                                <input
                                    id="register-password"
                                    v-model="registerForm.password"
                                    :type="showRegisterPassword ? 'text' : 'password'"
                                    :class="getInputClasses(registerErrors.password)"
                                    placeholder="Введите пароль"
                                    required
                                />
                                <button
                                    type="button"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3"
                                    @click="showRegisterPassword = !showRegisterPassword"
                                >
                                    <span class="text-gray-400 hover:text-gray-600">
                                        {{ showRegisterPassword ? '🙈' : '👁️' }}
                                    </span>
                                </button>
                            </div>
                            <p v-if="registerErrors.password" class="mt-2 text-sm text-red-600">
                                {{ registerErrors.password }}
                            </p>
                        </div>
                        
                        <!-- Подтверждение пароля поле -->
                        <div>
                            <label for="register-password-confirmation" class="block text-sm font-medium leading-6 text-gray-900">
                                Подтверждение пароля
                            </label>
                            <div class="relative mt-2">
                                <input
                                    id="register-password-confirmation"
                                    v-model="registerForm.password_confirmation"
                                    :type="showConfirmPassword ? 'text' : 'password'"
                                    :class="getInputClasses(registerErrors.password_confirmation)"
                                    placeholder="Подтвердите пароль"
                                    required
                                />
                                <button
                                    type="button"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                >
                                    <span class="text-gray-400 hover:text-gray-600">
                                        {{ showConfirmPassword ? '🙈' : '👁️' }}
                                    </span>
                                </button>
                            </div>
                            <p v-if="registerErrors.password_confirmation" class="mt-2 text-sm text-red-600">
                                {{ registerErrors.password_confirmation }}
                            </p>
                        </div>
                        
                        <!-- Кнопка регистрации -->
                        <div>
                            <button
                                type="submit"
                                :disabled="!isRegisterFormValid || isLoading"
                                class="group relative flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span v-if="isLoading" class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                {{ isLoading ? 'Регистрация...' : 'Зарегистрироваться' }}
                            </button>
                            <div v-if="!isRegisterFormValid && !isLoading" class="mt-2 text-xs text-gray-500 text-center">
                                Заполните все обязательные поля для регистрации
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template> 