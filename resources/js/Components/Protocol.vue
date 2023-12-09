<script setup>
import { useForm } from 'laravel-precognition-vue-inertia';
import { ref } from 'vue';
import Calendar from 'primevue/calendar';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import Modal from '@/Components/Modal.vue';

const openingModal = ref(false);

const openModal = () => {
    openingModal.value = true;
};

const closeModal = () => {
    openingModal.value = false;
};

const form = useForm('post', route('create.people'), {
    description: '',
    term: '',
    date: '',
    people_id: null
});

const createProtocolSubmit = () => form.submit({
    preserveScroll: true,
    onSuccess: () => {
        closeModal();
        form.reset();
        toast.success("Protocolo criado com Sucesso!", {
            position: 'top-right',
        });
    }
});
</script>

<template>
     <Modal :show="openingModal" @close="closeModal" :max-width="'4xl'">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Criar Protocolo
                </h3>
                <button @click="closeModal" type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-red-500 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center ">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="createProtocolSubmit">
                    <div class="grid md:grid-cols-2 gap-5">

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text">Descrição:*</span>
                            </label>
                            <Textarea rows="2" cols="30" placeholder="Escreva a Descrição"
                                v-model="form.description" class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm"
                                @change="form.validate('description')" />
                            <span v-if="form.invalid('description')" class="text-base text-red-500">
                                {{ form.errors.description }}
                            </span>
                        </div>

                        <div class="form-control w-full ">
                            <label class="label">
                                <span class="label-text">Data:*</span>
                            </label>
                            <Calendar v-model="form.date" dateFormat="dd/mm/yy" showIcon class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm"
                                @change="form.validate('date')" />
                            <span v-if="form.invalid('date')" class="text-base text-red-500">
                                {{ form.errors.date }}
                            </span>
                        </div>

                        <div class="form-control w-full ">
                            <label class="label">
                                <span class="label-text">Selecione o Demandente:*</span>
                            </label>
                            <Dropdown v-model="form.people_id" :options="peoples" optionLabel="name"
                                placeholder="Clique para Selecionar" @change="form.validate('people_id')"
                                class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm" />

                            <span v-if="form.invalid('people_id')" class="text-base text-red-500">
                                {{ form.errors.people_id }}
                            </span>
                        </div>

                        <div class="form-control w-full ">
                            <label class="label">
                                <span class="label-text">Prazo:*</span>
                            </label>
                            <Calendar v-model="form.term" dateFormat="dd/mm/yy" showIcon
                            class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm" @change="form.validate('term')" />
                            <span v-if="form.invalid('term')" class="text-base text-red-500">
                                {{ form.errors.term }}
                            </span>
                        </div>

                    </div>
                    <div class="flex justify-around mt-4">
                        <Button @click="closeModal" class="inline-flex items-center px-4 py-2 bg-red-500 border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Cancelar
                        </Button>
                        <Button type="submit" class="inline-flex items-center px-4 py-2 bg-green-500 border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Salvar
                        </Button>
                    </div>
                </form>
            </div>
        </Modal>
<div class="flex justify-center items-center m-2 p-4 border-b rounded-t">
    <button class="btn btn-neutral flex justify-center items-center m-8 py-3 px-2 text-white bg-pink-500 font-bold rounded-lg hover:bg-pink-700" @click="openModal">Criar Protocolo</button>
</div>
TABELA DE PROTOCOLOS
</template>