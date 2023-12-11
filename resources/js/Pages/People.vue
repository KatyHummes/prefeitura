<script setup>
import { useForm } from 'laravel-precognition-vue-inertia';
import { ref, computed } from 'vue';
import Calendar from 'primevue/calendar';
import InputMask from 'primevue/inputmask';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import Modal from '@/Components/Modal.vue';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import InputText from 'primevue/inputtext';
import { FilterMatchMode, FilterOperator } from 'primevue/api';

const toast = useToast();

const props = defineProps({
    peoples: Array,
});

const openingCreatePeopleModal = ref(false);

const openCreatePeopleModal = () => {
    openingCreatePeopleModal.value = true;
};

const closeCreatePeopleModal = () => {
    openingCreatePeopleModal.value = false;
};

const form = useForm('post', route('store.people'), {
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

const sexes2 = ref(['Feminino', 'Masculino', 'Outro']);

const peoples = computed(() => {
    return props.peoples.map(people => ({
        id: people.id,
        name: people.name,
        cpf: people.cpf,
        sex: people.sex,
        birth: new Date(people.birth),
    }));
});

const filters = ref();

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        name: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        birth: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }] },
        cpf: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }] },
        sex: { operator: FilterOperator.OR, constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }] },
    };
};

initFilters();

const clearFilter = () => {
    initFilters();
};

const formatDate = (value) => {
    return value.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};
const formatCPF = (value) => {
    // Remove caracteres não numéricos
    const cpf = value.replace(/\D/g, '');
    // Adiciona os pontos e o traço da máscara
    return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
};

const getSeverity = (sex) => {
    switch (sex) {
        case 'Masculino':
            return 'background-color: #0e11c4;';
        case 'Feminino':
            return 'background-color: #c40eb5;';
        case 'Outro':
            return 'background-color: #0ec41d;';
        default:
            return '';
    }
};
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-[var(--primary-color)] leading-tight">
                Pessoas
            </h2>
        </template>

        <div class="py-12">
            <div class="w-full sm:px-6 lg:px-8">
                <div class="bg-[var(--surface-50)] overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="flex flex-col justify-center items-center">
                        <button class="bg-[var(--surface-0)] text-[var(--text-color)] hover:text-[var(--primary-color)] flex justify-center items-center m-8 py-3 px-2 font-bold rounded-lg"
                            @click="openCreatePeopleModal">Criar Pessoa</button>
                    </div>
                        <DataTable v-model:filters="filters" :value="peoples" paginator showGridlines :rows="10"
                            dataKey="id" filterDisplay="menu" :globalFilterFields="['name', 'cpf', 'birth', 'sex']">
                            <template #header>
                                <div class="flex justify-between">
                                    <Button type="button" icon="pi pi-filter-slash" label="Limpar" outlined
                                        @click="clearFilter()" />
                                    <span class="p-input-icon-left">
                                        <i class="pi pi-search" />
                                        <InputText v-model="filters['global'].value"
                                            placeholder="Pesquise por Palavra chave" />
                                    </span>
                                </div>
                            </template>
                            <template #empty> Nenhuma pessoa encontrada! </template>
                            <Column field="name" header="Name" style="min-width: 12rem">
                                <template #body="{ data }">
                                    {{ data.name }}
                                </template>
                                <template #filter="{ filterModel }">
                                    <InputText v-model="filterModel.value" type="text" class="p-column-filter"
                                        placeholder="Pesquise o Nome" />
                                </template>
                            </Column>
                            <Column header="Nascimento" filterField="birth" dataType="date" style="min-width: 10rem">
                                <template #body="{ data }">
                                    {{ formatDate(data.birth) }}
                                </template>
                                <template #filter="{ filterModel }">
                                    <Calendar v-model="filterModel.value" dateFormat="dd/mm/yy" placeholder="dd/mm/yyyy"
                                        :manualInput="false" />
                                </template>
                            </Column>
                            <Column field="cpf" header="CPF" style="min-width: 12rem">
                                <template #body="{ data }">
                                    {{ formatCPF(data.cpf) }}
                                </template>
                                <template #filter="{ filterModel }">

                                    <!-- @input="filterModel.value = $event.target.value.replace(/\D/g, '')" -->

                                    <!-- <InputText v-model="filterModel.value" type="text" class="p-column-filter"
                                        placeholder="Pesquise o CPF" /> -->

                                    <InputMask v-model="filterModel.value" mask="999.999.999-99" class="p-column-filter"
                                        placeholder="Pesquise o CPF" :unmask=true />

                                </template>
                            </Column>
                            <Column header="Sexo" field="sex" :filterMenuStyle="{ width: '14rem' }"
                                style="min-width: 12rem">
                                <template #body="{ data }">
                                    <Tag :value="data.sex" :style="getSeverity(data.sex)" />
                                </template>
                                <template #filter="{ filterModel }">
                                    <Dropdown v-model="filterModel.value" :options="sexes2" placeholder="Pesquise o Sexo"
                                        class="p-column-filter" showClear>
                                        <template #option="slotProps">
                                            <Tag :value="slotProps.option" :severity="getSeverity(slotProps.option)"
                                                style="background: linear-gradient(to right, var(--primary-300), var(--primary-700))" />
                                        </template>
                                    </Dropdown>
                                </template>
                            </Column>
                            <Column headerStyle="width: 5rem; text-align: center"
                                bodyStyle="text-align: center; overflow: visible">
                                <template #body="{ data }">
                                    <div class="flex gap-4 items-center justify-center">
                                        <button type="button" @click="openUpdateUserModal(data)">
                                            <i class="text-2xl"></i>
                                        </button>
                                        <button type="button" @click="openDeleteUserConfirmModal(data)">
                                            <i class="text-2xl"></i>
                                        </button>
                                    </div>
                                </template>
                            </Column>
                        </DataTable>
                </div>
            </div>
        </div>

    </AppLayout>
    <Modal :show="openingCreatePeopleModal" @close="closeCreatePeopleModal" :max-width="'4xl'">
        <div class="flex items-start justify-between p-4 border-b rounded-t bg-[var(--surface-50)]">
            <h3 class="text-xl font-semibold text-[var(--text-color)] ">
                Criar pessoa
            </h3>
            <button @click="closeCreatePeopleModal" type="button"
                class="text-[var(--surface-50)] bg-transparent hover:bg-[var(--surface-0)] hover:text-[var(--red-500)] rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center ">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
        </div>
        <div class="p-6 bg-[var(--surface-50)]">
            <form @submit.prevent="CreateSubmit">
                <div class="grid md:grid-cols-2 gap-4">

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Nome:*</span>
                        </label>
                        <InputText type="text" placeholder="Nome Completo" v-model="form.name"
                            class="w-full md:w-14rem rounded-md shadow-sm" @change="form.validate('name')" />
                        <span v-if="form.invalid('name')" class="text-base text-[var(--red-500)]">
                            {{ form.errors.name }}
                        </span>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Data de Nascimento:*</span>
                        </label>
                        <!-- <input type="date" v-model="form.birth"> -->
                        <Calendar v-model="form.birth" dateFormat="dd/mm/yy" showIcon @change="form.validate('birth')"
                            placeholder="Clique para Selecionar" class="w-full md:w-14rem" />
                        <span v-if="form.invalid('birth')" class="text-base text-[var(--red-500)]">
                            {{ form.errors.birth }}
                        </span>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Cpf:*</span>
                        </label>
                        <InputMask v-model="form.cpf" mask="999.999.999-99" placeholder="000.000.000-00"
                            @change="form.validate('cpf')" class="w-full md:w-14rem" />

                        <span v-if="form.invalid('cpf')" class="text-base text-[var(--red-500)]">
                            {{ form.errors.cpf }}
                        </span>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Sexo:*</span>
                        </label>
                        <div class="card flex justify-content-center">
                            <Dropdown v-model="form.sex" :options="sexes" optionLabel="name"
                                placeholder="Clique para Selecionar" @change="form.validate('sex')"
                                class="w-full md:w-14rem" />
                        </div>
                        <span v-if="form.invalid('sex')" class="text-base text-[var(--red-500)]">
                            {{ form.errors.sex }}
                        </span>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Cidade:</span>
                        </label>
                        <InputText type="text" placeholder="Digite sua Cidade" v-model="form.city"
                            class="w-full md:w-14rem rounded-md shadow-sm" />
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Bairro:</span>
                        </label>
                        <InputText type="text" placeholder="Digite seu bairro" v-model="form.neighborhood"
                            class="w-full md:w-14rem rounded-md shadow-sm" />
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Rua:</span>
                        </label>
                        <InputText type="text" placeholder="Digite sua Rua" v-model="form.street"
                            class="w-full md:w-14rem  rounded-md shadow-sm" />
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Número da Rua:</span>
                        </label>
                        <InputText type="text" placeholder="Digite o número da Rua" v-model="form.number"
                            class="w-full md:w-14rem  rounded-md shadow-sm" />
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Complemento:</span>
                        </label>
                        <Textarea rows="5" cols="30" placeholder="Complemento de Endereço" v-model="form.complement"
                            class="w-full md:w-14rem  rounded-md shadow-sm" />
                    </div>


                </div>
                <div class="flex justify-around mt-4">
                    <Button @click="closeCreatePeopleModal"
                        class="inline-flex items-center px-4 py-2 bg-[var(--red-500)] border-[var(--gray-300)] rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-[var(--red-700)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Cancelar
                    </Button>
                    <Button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-[var(--green-500)] border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-[var(--green-700)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Salvar
                    </Button>
                </div>

            </form>
        </div>
    </Modal>
</template>
