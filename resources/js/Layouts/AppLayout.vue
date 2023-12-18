<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Modal from '@/Components/Modal.vue';
import { usePrimeVue } from 'primevue/config';

defineProps({
    title: String,
});

const PrimeVue = usePrimeVue();

const theme = ref(localStorage.getItem('theme') || 'lara-light-pink');

onMounted(() => {
    PrimeVue.changeTheme('lara-light-pink', theme.value, 'theme-link');
});

const changeTheme = (newTheme) => {
    PrimeVue.changeTheme(theme.value, newTheme, 'theme-link');
    localStorage.setItem('theme', newTheme);
    theme.value = newTheme;
};

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};

const showThemeModal = ref(false);
const openThemeModal = () => {
    showThemeModal.value = true;
};

const closeThemeModal = () => {
    showThemeModal.value = false;
};
</script>

<template>
    <div>

        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-[var(--surface-100)]">
            <nav class="bg-[var(--surface-0)] border-b border-[var(--surface-100)]">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('peoples')">
                                <ApplicationMark class="block h-9 w-auto" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex text[var(--text-color)]">
                                <NavLink :href="route('peoples')" :active="route().current('peoples')">
                                    Pessoas
                                </NavLink>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('protocols')" :active="route().current('protocols')">
                                    Protocolos
                                </NavLink>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <button @click="openThemeModal">
                                    Temas
                                </button>
                            </div>

                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <div class="ms-3 relative">
                                <!-- Teams Dropdown -->
                                <Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[var(--primary-color-text)] bg-[var(--surface-50)] hover:text-[var(--surface-50)] focus:outline-none focus:bg-[var(--surface-50)] active:bg-[var(--surface-50)] transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.current_team.name }}

                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="w-60">
                                            <!-- Team Management -->
                                            <div class="block px-4 py-2 text-xs text-[var(--text-color)]">
                                                Gerenciar equipe
                                            </div>

                                            <!-- Team Settings -->
                                            <DropdownLink :href="route('teams.show', $page.props.auth.user.current_team)">
                                                Configurações da equipe
                                            </DropdownLink>

                                            <DropdownLink v-if="$page.props.jetstream.canCreateTeams"
                                                :href="route('teams.create')">
                                                Criar nova equipe
                                            </DropdownLink>

                                            <!-- Team Switcher -->
                                            <template v-if="$page.props.auth.user.all_teams.length > 1">
                                                <div class="border-t text-[var(--text-color-secondary)]" />

                                                <div class="block px-4 py-2 text-xs text-[var(--text-color)]">
                                                    Trocar de equipe
                                                </div>

                                                <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                                    <form @submit.prevent="switchToTeam(team)">
                                                        <DropdownLink as="button">
                                                            <div class="flex items-center">
                                                                <svg v-if="team.id == $page.props.auth.user.current_team_id"
                                                                    class="me-2 h-5 w-5 text-[var(--green-400)]"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1.5"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>

                                                                <div>{{ team.name }}</div>
                                                            </div>
                                                        </DropdownLink>
                                                    </form>
                                                </template>
                                            </template>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos"
                                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-[var(--primary-color)] transition">
                                            <img class="h-8 w-8 rounded-full object-cover"
                                                :src="$page.props.auth.user.profile_photo_url"
                                                :alt="$page.props.auth.user.name">
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[var(--surface-900)] bg-[var(--surface-0)] hover:text-[var(--gray-700)] focus:outline-none focus:bg-[var(--surface-200)] active:bg-[var(--surface-100)] transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-black">
                                            Gerenciar conta
                                        </div>

                                        <DropdownLink :href="route('profile.show')">
                                            Perfil
                                        </DropdownLink>

                                        <DropdownLink v-if="$page.props.jetstream.hasApiFeatures"
                                            :href="route('api-tokens.index')">
                                            API Tokens
                                        </DropdownLink>

                                        <div class="border-t border-[var(-surface-100)]" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Sair
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                        <Modal :show="showThemeModal" @close="closeThemeModal" :maxWidth="'3xl'">

                            <div class="p-4">
                                <div class="mb-2 flex justify-between">
                                    <h1 class="text-2xl font-bold text-[var(--text-color)]">Escolha seu tema</h1>
                                    <button @click="closeThemeModal" type="button"
                                        class="text-[var(--gray-400)] bg-transparent hover:bg-[var(--surface-0)] hover:text-[var(--red-500)] rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <p>O lado esquerdo será o modo (claro ou escuro) e o direito é a cor principal do tema.</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-4 gap-y-8">
                                    <button @click="changeTheme('lara-light-purple')"
                                        class="border border-[#8B5CF6] flex justify-between items-center h-8">
                                        <span class="bg-[#ffffff] w-full h-full "></span>
                                        <span class="bg-[#8B5CF6] w-full h-full"></span>
                                    </button>

                                    <button @click="changeTheme('lara-light-blue')"
                                        class="border border-[#3B82F6] flex justify-between items-center h-8">
                                        <span class="bg-[#ffffff] w-full h-full"></span>
                                        <span class="bg-[#3B82F6] w-full h-full"></span>
                                    </button>

                                    <button @click="changeTheme('lara-light-indigo')"
                                        class="border border-[#6366F1] flex justify-between items-center h-8">
                                        <span class="bg-[#ffffff] w-full h-full"></span>
                                        <span class="bg-[#6366F1] w-full h-full"></span>
                                    </button>

                                    <button @click="changeTheme('lara-light-teal')"
                                        class="border border-[#10b981] flex justify-between items-center h-8">
                                        <span class="bg-[#ffffff] w-full h-full"></span>
                                        <span class="bg-[#10b981] w-full h-full"></span>
                                    </button>

                                    <button @click="changeTheme('lara-light-pink')"
                                        class="border border-[#ec4899] flex justify-between items-center h-8">
                                        <span class="bg-[#ffffff] w-full h-full"></span>
                                        <span class="bg-[#ec4899] w-full h-full"></span>
                                    </button>

                                    <button @click="changeTheme('lara-dark-purple')"
                                        class="border border-[#a78bfa] flex justify-between items-center h-8">
                                        <span class="bg-[#1f2937] w-full h-full "></span>
                                        <span class="bg-[#a78bfa] w-full h-full"></span>
                                    </button>

                                    <button @click="changeTheme('lara-dark-blue')"
                                        class="border border-[#60a5fa] flex justify-between items-center h-8">
                                        <span class="bg-[#1f2937] w-full h-full "></span>
                                        <span class="bg-[#60a5fa] w-full h-full"></span>
                                    </button>

                                    <button @click="changeTheme('lara-dark-indigo')"
                                        class="border border-[#818cf8] flex justify-between items-center h-8">
                                        <span class="bg-[#1f2937] w-full h-full "></span>
                                        <span class="bg-[#818cf8] w-full h-full"></span>
                                    </button>

                                    <button @click="changeTheme('lara-dark-teal')"
                                        class="border border-[#34d399] flex justify-between items-center h-8">
                                        <span class="bg-[#1f2937] w-full h-full "></span>
                                        <span class="bg-[#34d399] w-full h-full"></span>
                                    </button>

                                    <button @click="changeTheme('lara-dark-pink')"
                                        class="border border-[#ec4899] flex justify-between items-center h-8">
                                        <span class="bg-[#1f2937] w-full h-full "></span>
                                        <span class="bg-[#ec4899] w-full h-full"></span>
                                    </button>

                                </div>
                                <h1 class="my-4 text-xl font-bold text-[var(--text-color)]">Pré visualização do tema.</h1>
                                <div
                                    class="w-full p-4 rounded-2xl border-2 border-[var(--surface-900)] bg-[var(--surface-0)]">
                                    <h1 class="text-2xl text-[var(--primary-color)] text-center font-bold">Título exemplo
                                    </h1>
                                    <p class="text-sm text-[var(--text-color)] mt-4">Lorem Ipsum is simply dummy text of the
                                        printing and typesetting industry. Lorem
                                        Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                        unknown printer took a galley of type and scrambled it to make a type specimen book.
                                        It has survived not only five centuries, but also the leap into electronic
                                        typesetting, remaining essentially unchanged.</p>
                                </div>
                                <button class="mt-6 flex w-full justify-center items-center bg-[var(--surface-200)] rounded-xl p-4 text-[var(--text-color)] font-bold text-xl" @click="closeThemeModal">Fechar</button>
                            </div>

                        </Modal>
                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 rounded-md text-[var(--text-color)] hover:text-[var(--text-color-secondary)] hover:bg-[var(--surface-100)] focus:outline-none focus:bg-[var(--surface-100)] focus:text-[var(--text-color-secondary)] transition duration-150 ease-in-out"
                                @click="showingNavigationDropdown = !showingNavigationDropdown">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path
                                        :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }"
                    class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('peoples')" :active="route().current('peoples')">
                            Pessoas
                        </ResponsiveNavLink>
                    </div>
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('protocols')" :active="route().current('protocols')">
                            Protocolos
                        </ResponsiveNavLink>
                    </div>
                    <div class="pt-2 pb-3 space-y-1">
                        <button @click="openThemeModal" class="ml-4">
                            Tema
                        </button>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-[var(--surface-0)]">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 me-3">
                                <img class="h-10 w-10 rounded-full object-cover"
                                    :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                            </div>

                            <div>
                                <div class="font-medium text-base text-[var(--surface-900)]">
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="font-medium text-sm text-[var(--text-color-secondary)]">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                Perfil
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')"
                                :active="route().current('api-tokens.index')">
                                API Tokens
                            </ResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    Sair
                                </ResponsiveNavLink>
                            </form>

                            <!-- Team Management -->
                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                <div class="border-t border-[var(--surface-200)]" />

                                <div class="block px-4 py-2 text-xs text-[var(--text-color)]">
                                    Gerenciar equipe
                                </div>

                                <!-- Team Settings -->
                                <ResponsiveNavLink :href="route('teams.show', $page.props.auth.user.current_team)"
                                    :active="route().current('teams.show')">
                                    Configurações da equipe
                                </ResponsiveNavLink>

                                <ResponsiveNavLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')"
                                    :active="route().current('teams.create')">
                                    Criar nova equipe
                                </ResponsiveNavLink>

                                <!-- Team Switcher -->
                                <template v-if="$page.props.auth.user.all_teams.length > 1">
                                    <div class="border-t text-[var(--text-color-secondary)]" />

                                    <div class="block px-4 py-2 text-xs text-[var(--text-color)]">
                                        Trocar de equipe
                                    </div>

                                    <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                        <form @submit.prevent="switchToTeam(team)">
                                            <ResponsiveNavLink as="button">
                                                <div class="flex items-center">
                                                    <svg v-if="team.id == $page.props.auth.user.current_team_id"
                                                        class="me-2 h-5 w-5 text-[var(--green-400)]"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <div>{{ team.name }}</div>
                                                </div>
                                            </ResponsiveNavLink>
                                        </form>
                                    </template>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-[var(--surface-0)] shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
