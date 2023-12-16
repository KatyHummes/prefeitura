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
import Tag from 'primevue/tag';

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
    { id: 1, name: 'Feminino' },
    { id: 2, name: 'Masculino' },
    { id: 3, name: 'Outro' },
]);

const sexes2 = ref(['Feminino', 'Masculino', 'Outro']);

const peoples = computed(() => {
    return props.peoples.map(people => ({
        id: people.id,
        name: people.name,
        cpf: people.cpf,
        sex: people.sex,
        birth: new Date(people.birth),
        city: people.city,
        neighborhood: people.neighborhood,
        street: people.street,
        number: people.number,
        complement: people.complement,
    }));
});

// confg dos filtros
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

// confg da modal deletar pessoas
const showDeleteUserConfirmModal = ref(false);
const formDeleteUser = ref(null);
const openDeleteUserConfirmModal = (id) => {
    formDeleteUser.value = useForm('delete', `/excluir-pessoa/${id}`, {
        id: id,
    });
    showDeleteUserConfirmModal.value = true;
};
const closeDeletePeopleConfirmModal = () => {
    showDeleteUserConfirmModal.value = false;
};

const deleteUser = () => {
    formDeleteUser.value.submit({
        preserveScroll: true,
        onSuccess: () => {
            closeDeletePeopleConfirmModal();
            toast.success("Pessoa deletada com Sucesso!", {
                position: 'top-right',
            });
        },
        onError: () => {
            toast.error("Erro ao deletar Pessoa!", {
                position: 'top-right',
            });
        }
    });
};

// lógica da modal editar pessoas
const people = ref();
const showUpdatePeopleModal = ref(false);
const formUpdatePeople = ref(null);


const openUpdatePeopleModal = (peopleData) => {
    people.value = peopleData;
    console.log(peopleData);

    const selectedSex = sexes.value.find(s => s.name === peopleData.sex);

    formUpdatePeople.value = useForm('put', `/atualizar-pessoa/${peopleData.id}`, {
        id: peopleData.id,
        name: peopleData.name,
        birth: peopleData.birth,
        cpf: peopleData.cpf,
        sex: selectedSex || '',
        city: peopleData.city,
        neighborhood: peopleData.neighborhood,
        street: peopleData.street,
        number: peopleData.number,
        complement: peopleData.complement,
    });
    showUpdatePeopleModal.value = true;
};

const closeUpdatePeopleModal = () => {
    showUpdatePeopleModal.value = false;
};

const updatePeople = () => {
    formUpdatePeople.value.submit({
        preserveScroll: true,
        onSuccess: () => {
            closeUpdatePeopleModal();
            toast.success("Pessoa atualizada com Sucesso!", {
                position: 'top-right',
            });
        },
        onError: () => {
            toast.error("Erro ao atualizar pessoa!", {
                position: 'top-right',
            });
        }
    });
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
                        <button
                            class="bg-[var(--surface-0)] text-[var(--text-color)] hover:text-[var(--primary-color)] flex justify-center items-center m-8 py-3 px-2 font-bold rounded-lg"
                            @click="openCreatePeopleModal">Criar Pessoa</button>
                    </div>
                    <DataTable v-model:filters="filters" :value="peoples" paginator showGridlines :rows="10" :rowsPerPageOptions="[5, 10, 20, 50]" dataKey="id" scrollable  
                        filterDisplay="menu" :globalFilterFields="['name', 'cpf', 'birth', 'sex']" removableSort>
                        <template #header>
                            <div class="flex justify-between">
                                <Button type="button" icon="" label="Limpar" outlined
                                    @click="clearFilter()" />
                                <span>
                                    <i />
                                    <InputText v-model="filters['global'].value" placeholder="Pesquise por Palavra chave" />
                                </span>
                            </div>
                        </template>
                        <template #empty> Nenhuma pessoa encontrada! </template>

                        <Column field="id" header="ID" style="min-width: 4rem" sortable />

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

                                <InputMask v-model="filterModel.value" mask="999.999.999-99" class="p-column-filter"
                                    placeholder="Pesquise o CPF" :unmask=true />

                            </template>
                        </Column>

                        <Column header="Sexo" field="sex" :filterMenuStyle="{ width: '14rem' }" style="min-width: 12rem">
                            <template #body="{ data }">
                                <Tag :value="data.sex" :style="getSeverity(data.sex)" />
                            </template>
                            <template #filter="{ filterModel }">
                                <Dropdown v-model="filterModel.value" :options="sexes2" placeholder="Pesquise o Sexo"
                                    class="p-column-filter" showClear>
                                    <template #option="slotProps">
                                        <Tag :value="slotProps.option" :severity="getSeverity(slotProps.option)" class="!text-red-500"
                                            style="background: linear-gradient(to right, var(--primary-300), var(--primary-700))" />
                                    </template>
                                </Dropdown>
                            </template>
                        </Column>

                        <Column headerStyle="width:4rem" header="Ações" field="actions">
                            <template #body="{ data }">
                                <div class="flex justify-around">
                                    <button @click="openUpdatePeopleModal(data)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                    <button @click="openDeleteUserConfirmModal(data.id)" type="button">
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

    </AppLayout>

    <!-- Modal de Deletar Pessoa -->
    <Modal :show="showDeleteUserConfirmModal" @close="closeDeletePeopleConfirmModal">
        <form @submit.prevent="deleteUser()" class="bg-[var(--surface-50)] p-4">
            <h2 class="flex items-center justify-center p-4 m-4 font-bold text-[var(--text-color)]">Tem certeza que deseja
                excluir esta Pessoa?</h2>
            <div class="flex justify-between mt-12">
                <button type="button"
                class="inline-flex items-center px-4 py-2 bg-[var(--red-500)] border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-[var(--red-700)] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                    @click="closeDeletePeopleConfirmModal">Cancelar</button>
                <button type="submit"
                class="inline-flex items-center px-8 py-4 bg-[var(--green-500)] border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-[var(--green-700)] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">Excluir </button>
            </div>

        </form>
    </Modal>

    <!-- Modal de Editar Pessoa -->
    <Modal :show="showUpdatePeopleModal" @close="closeUpdatePeopleModal" :max-width="'4xl'">
        <div class="flex items-start justify-between p-4 border-b rounded-t">
            <h3 class="text-xl font-semibold text-[var(--text-color)] ">
                Editar Pessoa
            </h3>
            <button @click="closeUpdatePeopleModal" type="button"
                class="text-[var(--gray-400)] bg-transparent hover:bg-[var(--surface-0)] hover:text-[var(--red-500)] rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
        </div>
        <div class="p-6 bg-[var(--surface-50)]">
            <form @submit.prevent="updatePeople">
                <div class="grid md:grid-cols-2 gap-4">

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Nome:*</span>
                        </label>
                        <InputText type="text" placeholder="Nome Completo" v-model="formUpdatePeople.name"
                            class="w-full md:w-14rem rounded-md shadow-sm" @change="formUpdatePeople.validate('name')" />
                        <span v-if="formUpdatePeople.invalid('name')" class="text-base text-[var(--red-500)]">
                            {{ formUpdatePeople.errors.name }}
                        </span>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Data de Nascimento:*</span>
                        </label>
                        <!-- <input type="date" v-model="form.birth"> -->
                        <Calendar v-model="formUpdatePeople.birth" dateFormat="dd/mm/yy" showIcon
                            @change="formUpdatePeople.validate('birth')" placeholder="Clique para Selecionar"
                            class="w-full md:w-14rem" />
                        <span v-if="formUpdatePeople.invalid('birth')" class="text-base text-[var(--red-500)]">
                            {{ formUpdatePeople.errors.birth }}
                        </span>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Cpf:*</span>
                        </label>
                        <InputMask v-model="formUpdatePeople.cpf" mask="999.999.999-99" placeholder="000.000.000-00"
                            @change="formUpdatePeople.validate('cpf')" class="w-full md:w-14rem" />

                        <span v-if="formUpdatePeople.invalid('cpf')" class="text-base text-[var(--red-500)]">
                            {{ formUpdatePeople.errors.cpf }}
                        </span>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Sexo:*</span>
                        </label>
                        <div class="card flex justify-content-center">
                            <Dropdown v-model="formUpdatePeople.sex" :options="sexes" placeholder="Clique para Selecionar"
                                @change="formUpdatePeople.validate('sex')" class="w-full md:w-14rem" optionLabel="name" />
                        </div>
                        <span v-if="formUpdatePeople.invalid('sex')" class="text-base text-[var(--red-500)]">
                            {{ formUpdatePeople.errors.sex }}
                        </span>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Cidade:</span>
                        </label>
                        <InputText type="text" placeholder="Digite sua Cidade" v-model="formUpdatePeople.city"
                            class="w-full md:w-14rem rounded-md shadow-sm" />
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Bairro:</span>
                        </label>
                        <InputText type="text" placeholder="Digite seu bairro" v-model="formUpdatePeople.neighborhood"
                            class="w-full md:w-14rem rounded-md shadow-sm" />
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Rua:</span>
                        </label>
                        <InputText type="text" placeholder="Digite sua Rua" v-model="formUpdatePeople.street"
                            class="w-full md:w-14rem  rounded-md shadow-sm" />
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Número da Rua:</span>
                        </label>
                        <InputText type="text" placeholder="Digite o número da Rua" v-model="formUpdatePeople.number"
                            class="w-full md:w-14rem  rounded-md shadow-sm" />
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Complemento:</span>
                        </label>
                        <Textarea rows="5" cols="30" placeholder="Complemento de Endereço"
                            v-model="formUpdatePeople.complement" class="w-full md:w-14rem  rounded-md shadow-sm" />
                    </div>


                </div>
                <div class="flex justify-between mt-12">
                    <Button @click="closeModal" type="button"
                        class="inline-flex items-center px-4 py-2 bg-[var(--red-500)] border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-[var(--red-700)] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Cancelar
                    </Button>
                    <Button type="submit"
                        class="inline-flex items-center px-8 py-4 bg-[var(--green-500)] border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-[var(--green-700)] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Salvar
                    </Button>
                </div>

            </form>
        </div>
    </Modal>

    <!-- Modal de Criar Pessoa -->
    <Modal :show="openingCreatePeopleModal" @close="closeCreatePeopleModal" :max-width="'4xl'">
        <div class="flex items-start justify-between p-4 border-b rounded-t bg-[var(--surface-50)]">
            <h3 class="text-xl font-semibold text-[var(--text-color)] ">
                Criar pessoa
            </h3>
            <button @click="closeCreatePeopleModal" type="button"
                class="text-[var(--surface-900)] bg-transparent hover:bg-[var(--surface-0)] hover:text-[var(--red-500)] rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center ">
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
                <div class="flex justify-between mt-12">
                    <Button @click="closeModal" type="button"
                        class="inline-flex items-center px-4 py-2 bg-[var(--red-500)] border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-[var(--red-700)] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Cancelar
                    </Button>
                    <Button type="submit"
                        class="inline-flex items-center px-8 py-4 bg-[var(--green-500)] border border-gray-300 rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-[var(--green-700)] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Salvar
                    </Button>
                </div>

            </form>
        </div>
    </Modal>
</template>

<style>
.p-tag-value{
    color: white;
}
</style>