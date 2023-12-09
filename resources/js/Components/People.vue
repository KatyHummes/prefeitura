<script setup>
import { useForm } from 'laravel-precognition-vue-inertia';
import { ref } from 'vue';
import Calendar from 'primevue/calendar';
import InputMask from 'primevue/inputmask';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';

const openingCreatePeopleModal = ref(false);

const openCreatePeopleModal = () => {
    openingCreatePeopleModal.value = true;
};

const closeCreatePeopleModal = () => {
    openingCreatePeopleModal.value = false;
};

const form = useForm('post', route('create.protocol'), {
    name: '',
    birth: '',
    cpf: '',
    sex: '',
    city: '',
    neighborhood: '',
    street: '',
    number: '',
    complement: '',
});


const CreateSubmit = () => form.submit({
    preserveScroll: true,
    onSuccess: () => {
        closeCreatePeopleModal();
        form.reset();
        toast.success("Pessoa criada com Sucesso!", {
            position: 'top-right',
        });
    }
});

const sexes = ref([
    { id: 1, name: 'Feminino', code: 'FM' },
    { id: 2, name: 'Masculino', code: 'MC' },
    { id: 3, name: 'Outro', code: 'OT' },
]);


</script>

<template>
<div class="flex items-center justify-center p-4 border-b rounded-t dark:border-gray-700">
    <div>
        <Modal :show="openingCreatePeopleModal" @close="closeCreatePeopleModal" :max-width="'4xl'">
            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Criar pessoa
                </h3>
                <button @click="closeCreatePeopleModal" type="button"
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
                <form @submit.prevent="CreateSubmit">
                    <div class="grid md:grid-cols-2 gap-4">

                        <div class="form-control w-full ">
                            <label class="label">
                                <span class="label-text">Nome:*</span>
                            </label>
                            <TextInput type="text" placeholder="Nome Completo" v-model="form.name" class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm"
                                @change="form.validate('name')" />
                            <span v-if="form.invalid('name')" class="text-base text-red-500">
                                {{ form.errors.name }}
                            </span>
                        </div>

                        <div class="form-control w-full ">
                            <label class="label">
                                <span class="label-text">Data de Nascimento:*</span>
                            </label>
                            <!-- <input type="date" v-model="form.birth"> -->
                            <Calendar v-model="form.birth" dateFormat="dd/mm/yy" showIcon @change="form.validate('birth')"
                                placeholder="Clique para Selecionar" class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm" />
                            <span v-if="form.invalid('birth')" class="text-base text-red-500">
                                {{ form.errors.birth }}
                            </span>
                        </div>

                        <div class="form-control w-full ">
                            <label class="label">
                                <span class="label-text">Cpf:*</span>
                            </label>
                            <InputMask v-model="form.cpf" mask="999.999.999-99" placeholder="000.000.000-00"
                                @change="form.validate('cpf')" class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm"/>

                            <span v-if="form.invalid('cpf')" class="text-base text-red-500">
                                {{ form.errors.cpf }}
                            </span>
                        </div>

                        <div class="form-control w-full ">
                            <label class="label">
                                <span class="label-text">Sexo:*</span>
                            </label>
                            <div class="card flex justify-content-center">
                                <Dropdown v-model="form.sex" :options="sexes" optionLabel="name"
                                    placeholder="Clique para Selecionar" 
                                    @change="form.validate('sex')" class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm" />
                            </div>
                            <span v-if="form.invalid('sex')" class="text-base text-red-500">
                                {{ form.errors.sex }}
                            </span>
                        </div>

                        <div class="form-control w-full ">
                            <label class="label">
                                <span class="label-text">Cidade:</span>
                            </label>
                            <TextInput type="text" placeholder="Digite sua Cidade" v-model="form.city" class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm"/>
                        </div>

                        <div class="form-control w-full ">
                            <label class="label">
                                <span class="label-text">Bairro:</span>
                            </label>
                            <TextInput type="text" placeholder="Digite seu bairro" v-model="form.neighborhood" class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm" />
                        </div>

                        <div class="form-control w-full ">
                            <label class="label">
                                <span class="label-text">Rua:</span>
                            </label>
                            <TextInput type="text" placeholder="Digite sua Rua" v-model="form.street" class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm"/>
                        </div>

                        <div class="form-control w-full ">
                            <label class="label">
                                <span class="label-text">Número da Rua:</span>
                            </label>
                            <TextInput type="text" placeholder="Digite o número da Rua" v-model="form.number" class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm"/>
                        </div>

                        <div class="form-control w-full ">
                            <label class="label">
                                <span class="label-text">Complemento:</span>
                            </label>
                            <Textarea rows="5" cols="30" placeholder="Complemento de Endereço" v-model="form.complement"
                             class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm" />
                        </div>


                    </div>
                    <div class="flex justify-around mt-4">
                        <Button @click="closeCreatePeopleModal" class="inline-flex items-center px-4 py-2 bg-red-500 border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Cancelar
                        </Button>
                        <Button type="submit" class="inline-flex items-center px-4 py-2 bg-green-500 border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Salvar
                        </Button>
                    </div>

                </form>
            </div>
        </Modal>
    </div>
    <button class="btn btn-neutral flex justify-center items-center m-8 py-3 px-2  text-white bg-pink-500 font-bold rounded-lg hover:bg-pink-700" @click="openCreatePeopleModal">Criar pessoa</button>
</div>
TABELA DE PESSOAS
</template>
