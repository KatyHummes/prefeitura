<script setup>
import { useForm } from 'laravel-precognition-vue-inertia';
import { ref } from 'vue';
import Calendar from 'primevue/calendar';
import InputMask from 'primevue/inputmask';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import TextInput from '@/Components/TextInput.vue';


const form = useForm('post', route('create.people'), {
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
    <div>
        <div class="p-6">
            <form @submit.prevent="CreateSubmit">
                <div class="grid md:grid-cols-2 gap-4">

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Nome:*</span>
                        </label>
                        <TextInput type="text" placeholder="Nome Completo" v-model="form.name"
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
                        <Calendar v-model="form.birth" dateFormat="dd/mm/yy" showIcon
                            @change="form.validate('birth')" placeholder="Clique para Selecionar" />
                        <span v-if="form.invalid('birth')" class="text-base text-red-500">
                            {{ form.errors.birth }}
                        </span>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Cpf:*</span>
                        </label>
                        <InputMask v-model="form.cpf" mask="999.999.999-99" placeholder="000.000.000-00"
                            @change="form.validate('cpf')" />

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
                                placeholder="Clique para Selecionar" class="w-full md:w-14rem"
                                @change="form.validate('sex')" />
                        </div>
                        <span v-if="form.invalid('sex')" class="text-base text-red-500">
                            {{ form.errors.sex }}
                        </span>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Cidade:</span>
                        </label>
                        <TextInput type="text" placeholder="Digite sua Cidade" v-model="form.city" />
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Bairro:</span>
                        </label>
                        <TextInput type="text" placeholder="Digite seu bairro" v-model="form.neighborhood" />
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Rua:</span>
                        </label>
                        <TextInput type="text" placeholder="Digite sua Rua" v-model="form.street" />
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Número da Rua:</span>
                        </label>
                        <TextInput type="text" placeholder="Digite o número da Rua" v-model="form.number" />
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text">Complemento:</span>
                        </label>
                        <Textarea rows="5" cols="30" placeholder="Complemento de Endereço"
                            v-model="form.complement" />
                    </div>


                </div>

                <button type="submit" class="rounded-lg px-2 py-1 text-white bg-green-500">Salvar</button>

            </form>
        </div>
    </div>
</template>
