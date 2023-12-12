<script setup>
import { useForm } from 'laravel-precognition-vue-inertia';
import Calendar from 'primevue/calendar';
import InputMask from 'primevue/inputmask';
import Textarea from 'primevue/textarea';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    people: Object,
});

const form = useForm('put', route('people.update', { id: props.people.id }), {
    name: props.people.name,
    birth: props.people.birth,
    cpf: props.people.cpf,
    sex: props.people.sex,
    city: props.people.city,
    neighborhood: props.people.neighborhood,
    street: props.people.street,
    number: props.people.number,
    complement: props.people.complement,
});

const Submit = () => form.submit({
    preserveScroll: true,
    onSuccess: () => {
        closeCreatePeopleModal();
        form.reset();
        toast.success("Pessoa criada com Sucesso!", {
            position: 'top-right',
        });
    }
});

const sexes = ['Feminino', 'Masculino', 'Outro'];

</script>]

<template>
    <AppLayout title="edit-people">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Escola
            </h2>
        </template>
        <div class="p-6 bg-[var(--surface-50)]">
            <form @submit.prevent="Submit">
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
                            <Dropdown v-model="form.sex" :options="sexes" 
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
                    <Button type="button"
                        class="inline-flex items-center px-4 py-2 bg-[var(--red-500)] border-[var(--gray-300)] rounded-md font-semibold text-xs text-[var(--text-color)] uppercase tracking-widest shadow-sm hover:bg-[var(--red-700)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Cancelar
                    </Button>
                    <Button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-[var(--green-500)] border border-[var(--text-color)] rounded-md font-semibold text-xs text-[var(--text-color)] uppercase tracking-widest shadow-sm hover:bg-[var(--green-700)] focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Salvar
                    </Button>
                </div>

            </form>
        </div>

    </AppLayout>
</template>