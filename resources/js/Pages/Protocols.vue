<script setup>
import { useForm } from 'laravel-precognition-vue-inertia';
import { ref, computed } from 'vue';
import Calendar from 'primevue/calendar';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import Modal from '@/Components/Modal.vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { FilterMatchMode, FilterOperator } from 'primevue/api'
import InputText from 'primevue/inputtext';

const toast = useToast();

const props = defineProps({
    peoples: Array,
    protocols: Array,
});

// modal criar protocolo
const openingModal = ref(false);

const openModal = () => {
    openingModal.value = true;
};

const closeModal = () => {
    openingModal.value = false;
};

const form = useForm('post', route('store.protocol'), {
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

const protocols = computed(() => {
    return props.protocols.map(protocol => ({
        id: protocol.id,
        people: protocol.people.name,
        date: new Date(protocol.date),
        description: protocol.description,
        term: new Date(protocol.term),
    }));
});

// filtros da tabela
const filters = ref();

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        people: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        number: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        description: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }] },
        term: { operator: FilterOperator.AND, constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }] },
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

// confg. do calendario data e prazo
let today = new Date();
let month = today.getMonth();
let year = today.getFullYear();
let prevMonth = (month === 0) ? 11 : month - 1;
let prevYear = (prevMonth === 11) ? year - 1 : year;
let nextMonth = (month === 11) ? 0 : month + 1;
let nextYear = (nextMonth === 0) ? year + 1 : year;

const currentDate = new Date();
const minDateForDate = ref(new Date());
const maxDateForDate = ref(new Date());
const minDateForTerm = ref(new Date());
const maxDateForTerm = ref(new Date());

minDateForDate.value.setDate(currentDate.getDate() - 60);
minDateForDate.value.setFullYear(prevYear); // Definir o mínimo para datas retroativas
maxDateForDate.value.setFullYear(nextYear);
minDateForTerm.value.setFullYear(year); // Definir o mínimo para prazo começando a partir de hoje
maxDateForTerm.value.setFullYear(nextYear);

// confg da modal deletar protocolo
const showDeleteProtocolConfirmModal = ref(false);

const formDeleteProtocol = ref(null);

const openDeleteProtocolConfirmModal = (id) => {
    formDeleteProtocol.value = useForm('delete', `/excluir-protocolo/${id}`, {
        id: id,
    });
    showDeleteProtocolConfirmModal.value = true;
};

const closeDeleteProtocolConfirmModal = () => {
    showDeleteProtocolConfirmModal.value = false;
};

const deleteProtocol = () => {
    formDeleteProtocol.value.submit({
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteProtocolConfirmModal();
            toast.success("Protocolo deletado com Sucesso!", {
                position: 'top-right',
            });
        },
        onError: () => {
            toast.error("Erro ao deletar Protocolo!", {
                position: 'top-right',
            });
        }
    });
};

// lógica da modal editar protocolo
const protocol = ref();
const showUpdateProtocolModal = ref(false);
const formUpdateProtocol = ref(null);
// const openUpdateProtocolModal = (protocolData) => {
//     protocol.value = protocolData;
//     console.log(protocolData)
//     formUpdateProtocol.value = useForm('put', `/update-protocol/${protocolData.id}`, {
//         id: protocolData.id,
//         people: protocolData.people,
//         description: protocolData.description,
//         date: protocolData.date,
//         term: protocolData.term,
//     });
//     showUpdateProtocolModal.value = true;
// };

const openUpdateProtocolModal = (protocolData) => {
    protocol.value = protocolData;
    console.log(protocolData);

    // Encontrar o objeto do usuário correspondente no array 'peoples'
    const selectedPeople = props.peoples.find(p => p.name === protocolData.people);

    formUpdateProtocol.value = useForm('put', `/update-protocol/${protocolData.id}`, {
        id: protocolData.id,
        people: selectedPeople || '', // Use o objeto completo ou um valor padrão se não for encontrado
        description: protocolData.description,
        date: protocolData.date,
        term: protocolData.term,
    });
    showUpdateProtocolModal.value = true;
};

const closeUpdateProtocolModal = () => {
    showUpdateProtocolModal.value = false;
};

const updateProtocol = () => {
    formUpdateProtocol.value.submit({
        preserveScroll: true,
        onSuccess: () => {
            closeUpdateProtocolModal();
            toast.add({
                severity: 'success',
                summary: 'Sucesso',
                detail: 'Usuário atualizado com Sucesso!',
                life: 3000
            });
        },
        onError: () => {
            toast.add({
                severity: 'error',
                summary: 'Erro',
                detail: 'Erro ao atualizar o usuário.',
                life: 3000
            });
        }
    });
};

</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-[var(--primary-color)] leading-tight">
                Protocolos
            </h2>
        </template>

        <div class="py-12">
            <div class="w-full sm:px-6 lg:px-8">
                <div class="bg-[var(--surface-50)] overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="flex flex-col justify-center items-center">

                        <div class="flex flex-col justify-center items-center">
                            <button
                                class="bg-[var(--surface-0)] text-[var(--text-color)] hover:text-[var(--primary-color)] flex justify-center items-center m-8 py-3 px-2 font-bold rounded-lg"
                                @click="openCreatePeopleModal">Criar Protocolo</button>
                        </div>
                        <DataTable v-model:filters="filters" :value="protocols" paginator showGridlines :rows="10"
                            dataKey="id" filterDisplay="menu" :globalFilterFields="['people', 'date', 'term', 'description']">
                            <template #header>
                                <div class="flex justify-between">
                                    <Button type="button" icon="pi pi-filter-slash" label="Clear" outlined
                                        @click="clearFilter()" />
                                    <span class="p-input-icon-left">
                                        <i class="pi pi-search" />
                                        <InputText v-model="filters['global'].value"
                                            placeholder="Pesquise por Palavra chave" />
                                    </span>
                                </div>
                            </template>
                            <template #empty> Nenhum protocolo encontrado! </template>
                            <Column field="people" header="Pessoa" style="min-width: 12rem">
                                <template #body="{ data }">
                                    {{ data.people }}
                                </template>
                                <template #filter="{ filterModel }">
                                    <InputText v-model="filterModel.value" type="text" class="p-column-filter"
                                        placeholder="Pesquise pela Descrição" />
                                </template>
                            </Column>
                            <Column field="description" header="Descrição" style="min-width: 12rem">
                                <template #body="{ data }">
                                    {{ data.description }}
                                </template>
                                <template #filter="{ filterModel }">
                                    <InputText v-model="filterModel.value" type="text" class="p-column-filter"
                                        placeholder="Pesquise pela Descrição" />
                                </template>
                            </Column>
                            <Column header="Data" dataType="date" filterField="date" style="min-width: 10rem">
                                <template #body="{ data }">
                                    {{ formatDate(data.date) }}
                                </template>
                                <template #filter="{ filterModel }">
                                    <Calendar v-model="filterModel.value" type="text" class="p-column-filter"
                                        placeholder="Pesquise pela Descrição" dateFormat="dd/mm/yy" />
                                </template>
                            </Column>
                            <Column header="Prazo" filterField="term" dataType="date" style="min-width: 10rem">
                                <template #body="{ data }">
                                    {{ formatDate(data.term) }}
                                </template>
                                <template #filter="{ filterModel }">
                                    <Calendar v-model="filterModel.value" dateFormat="dd/mm/yy" placeholder="dd/mm/yyyy"
                                        :manualInput="false" />
                                </template>
                            </Column>
                            <Column header="Editar" field="actions">
                                <template #body="{ data }">
                                    <div class="flex justify-around">
                                        <button @click="openUpdateProtocolModal(data)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                    <button @click="openDeleteProtocolConfirmModal(data.id)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                    </div>
                                </template>
                            </Column>                
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
    <!-- modal de deletar protocolo   -->
    <Modal :show="showDeleteProtocolConfirmModal" @close="closeDeleteProtocolConfirmModal">
        <form @submit.prevent="deleteProtocol()" class="bg-[var(--surface-50)]">
            <h2 class="flex items-center justify-center p-4 m-4 font-bold text-[var(--text-color)]">Tem certeza que deseja excluir
                esta Pessoa?</h2>
            <div class="flex justify-around">
                <button type="button"
                    class="bg-[var(--green-500)] text-[var(--primary-color-text)] rounded-md m-4 px-2 py-1"
                    @click="closeDeleteProtocolConfirmModal">Cancelar</button>
                <button type="submit"
                    class="bg-[var(--red-500)] text-[var(--primary-color-text)] rounded-md m-4 px-2 py-1">Excluir </button>
            </div>
        </form>
    </Modal>
    <!-- modal de editar protocolo -->
    <Modal :show="showUpdateProtocolModal" @close="closeUpdateProtocolModal" :max-width="'4xl'">
        <div class="flex items-start justify-between p-4 border-b rounded-t ">
            <h3 class="text-xl font-semibold text-gray-900 ">
                Editar Protocolo
            </h3>
            <button @click="closeUpdateProtocolModal" type="button"
                class="text-[var(--gray-400)] bg-transparent hover:bg-[var(--gray-200)] hover:text-[var(--red-500)] rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center ">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
        </div>
        <div class="p-6">
            <form @submit.prevent="updateProtocol">
                <div class="grid md:grid-cols-2 gap-5">

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Descrição:*</span>
                        </label>
                        <Textarea rows="2" cols="30" placeholder="Escreva a Descrição" v-model="formUpdateProtocol.description"
                            class="w-full md:w-14rem rounded-md shadow-sm :focus:border-[var(--primary-color)]"
                            @change="formUpdateProtocol.validate('description')" />
                        <span v-if="formUpdateProtocol.invalid('description')" class="text-base text-[var(--red-500)]">
                            {{ formUpdateProtocol.errors.description }}
                        </span>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Data:*</span>
                        </label>
                        <Calendar v-model="formUpdateProtocol.date" dateFormat="dd/mm/yy" showIcon :minDate="minDateForDate"
                            :maxDate="currentDate" class="w-full md:w-14rem rounded-md shadow-sm"
                            @change="formUpdateProtocol.validate('date')" />
                        <span v-if="formUpdateProtocol.invalid('date')" class="text-base text-[var(--red-500)]">
                            {{ formUpdateProtocol.errors.date }}
                        </span>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Selecione o Demandente:* {{ formUpdateProtocol.people }}</span>
                        </label>
                        <Dropdown v-model="formUpdateProtocol.people" :options="peoples" optionLabel="name"
                            placeholder="Clique para Selecionar" @change="formUpdateProtocol.validate('people')"
                            class="w-full md:w-14rem rounded-md shadow-sm" />

                        <span v-if="formUpdateProtocol.invalid('people')" class="text-base text-[var(--red-500)]">
                            {{ formUpdateProtocol.errors.people }}
                        </span>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Prazo:*</span>
                        </label>
                        <Calendar v-model="formUpdateProtocol.term" dateFormat="dd/mm/yy" showIcon :minDate="currentDate"
                            :maxDate="maxDateForTerm"
                            class="w-full md:w-14rem rounded-md shadow-sm"
                            @change="formUpdateProtocol.validate('term')" />
                        <span v-if="formUpdateProtocol.invalid('term')" class="text-base text-[var(--red-500)]">
                            {{ formUpdateProtocol.errors.term }}
                        </span>
                    </div>

                </div>
            </form>
        </div>

    </Modal>
    <!-- modal de criar protocolo   -->
    <Modal :show="openingModal" @close="closeModal" :max-width="'4xl'">
        <div class="flex items-start justify-between p-4 border-b rounded-t ">
            <h3 class="text-xl font-semibold text-gray-900 ">
                Criar Protocolo
            </h3>
            <button @click="closeModal" type="button"
                class="text-[var(--gray-400)] bg-transparent hover:bg-(--gray-200)] hover:text-[var(--red-500)] rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center ">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
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
                        <Textarea rows="2" cols="30" placeholder="Escreva a Descrição" v-model="form.description"
                            class="w-full md:w-14rem rounded-md shadow-sm :focus:border-[var(--primary-color)]"
                            @change="form.validate('description')" />
                        <span v-if="form.invalid('description')" class="text-base text-[var(--red-500)]">
                            {{ form.errors.description }}
                        </span>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Data:*</span>
                        </label>
                        <Calendar v-model="form.date" dateFormat="dd/mm/yy" showIcon :minDate="minDateForDate"
                            :maxDate="currentDate" class="w-full md:w-14rem rounded-md shadow-sm"
                            @change="form.validate('date')" />
                        <span v-if="form.invalid('date')" class="text-base text-[var(--red-500)]">
                            {{ form.errors.date }}
                        </span>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Selecione o Demandente:*</span>
                        </label>
                        <Dropdown v-model="form.people_id" :options="peoples" optionLabel="name"
                            placeholder="Clique para Selecionar" @change="form.validate('people_id')"
                            class="w-full md:w-14rem rounded-md shadow-sm" />

                        <span v-if="form.invalid('people_id')" class="text-base text-[var(--red-500)]">
                            {{ form.errors.people_id }}
                        </span>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text">Prazo:*</span>
                        </label>
                        <Calendar v-model="form.term" dateFormat="dd/mm/yy" showIcon :minDate="currentDate"
                            :maxDate="maxDateForTerm"
                            class="w-full md:w-14rem border-gray-300 focus:border-pink-500 focus:ring-pink-500 bg-pink-100 rounded-md shadow-sm"
                            @change="form.validate('term')" />
                        <span v-if="form.invalid('term')" class="text-base text-[var(--red-500)]">
                            {{ form.errors.term }}
                        </span>
                    </div>

                </div>
                <div class="flex justify-around mt-4">
                    <Button @click="closeModal"
                        class="inline-flex items-center px-4 py-2 bg-[var(--red-500)] border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-[var(--red-700)] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Cancelar
                    </Button>
                    <Button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-[var(--green-500)] border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-[var(--green-700)] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Salvar
                    </Button>
                </div>
            </form>
        </div>
    </Modal>
</template>