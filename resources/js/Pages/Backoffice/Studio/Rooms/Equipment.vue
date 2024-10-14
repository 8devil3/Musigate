<template>
    <ContentLayout
        @submitted="submit()"
        :title="props.room.name"
        icon="fa-solid fa-sliders"
        :tabLinks="tabLinks"
        :backRoute="route('sale-prova.index')"
    >
        <template #content>
            <FormElement>
                <template #title>
                    {{ form.category_name }}
                </template>

                <template #description>
                    Seleziona la categoria e inserisci l'equipaggiamento.
                    Puoi inserirlo singolarmente oppure copiare un elenco di testo e incollarlo nella finestra che compare cliccando su "Aggiungi in massa".
                </template>

                <template #content>
                    <div v-if="form.equipments.length" class="space-y-3">
                        <template v-for="equip, index in form.equipments">
                            <div class="flex gap-2">
                                <Input v-model="equip.name" placeholder="Inserisci equipaggiamento" class="w-full" required/>
                                <ActionButton @click="removeEquip(index)" icon="fa-solid fa-trash-can" color="red" title="Rimuovi equipaggiamento"/>
                            </div>
                        </template>

                        <div class="px-4 space-x-8">
                            <button type="button" @click="addEquip()" title="Aggiungi singolo" class="font-medium text-orange-500 transition-colors hover:text-orange-400">+ Aggiungi singolo</button>
                            <button type="button" @click="openModalBulk()" title="Aggiungi in massa" class="font-medium text-orange-500 transition-colors hover:text-orange-400">+ Aggiungi in massa</button>
                        </div>
                    </div>

                    <Empty v-else icon="fa-solid fa-sliders">
                        <template #title>
                            Nessun equipaggiamento presente
                        </template>

                        <template #description>
                            Clicca sul pulsante "Aggiungi singolo" o "Aggiungi in massa" per aggiungerne.
                        </template>

                        <template #actions>
                            <div class="flex flex-col items-center gap-2 mx-auto max-w-48">
                                <Button @click="addEquip()" text="Aggiungi singolo" icon="fa-solid fa-plus" title="Aggiungi singolo" class="w-full" />
                                <Button @click="openModalBulk()" text="Aggiungi in massa" icon="fa-solid fa-square-plus" title="Aggiungi in massa" class="w-full" />
                            </div>
                        </template>
                    </Empty>
                </template>
            </FormElement>
        </template>

        <template #actions>
            <Select v-model.number="form.current_category_id" @change="selectCategory()" :options="props.categories" default="Seleziona categoria" class="w-40" />

            <SaveButton v-if="form.isDirty && !form.processing" />
        </template>
    </ContentLayout>

    <Modal :isOpen="isOpenModal" @close="closeModalBulk()">
        <template #title>{{ form.category_name }} - Aggiungi in massa</template>
        <template #description>
            Incolla qui l'elenco dell'equipaggiamento copiato.<br>
            Ogni singola riga corrisponde ad un equipaggiamento.
            <Textarea v-model="bulkText" class="w-full mt-4" />
        </template>
        <template #actions>
            <div class="space-x-2">
                <Button @click="addEquipBulk()" text="Ok, aggiungi" color="green" />
                <Button @click="closeModalBulk()" text="Annulla" color="gray" />
            </div>
        </template>
    </Modal>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import Input from '@/Components/Form/Input.vue'
import Modal from '@/Components/Modal.vue';
import Button from '@/Components/Form/Button.vue';
import Select from '@/Components/Form/Select.vue';
import Textarea from '@/Components/Form/Textarea.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import Empty from '@/Components/Backoffice/Empty.vue';

const props = defineProps({
    room: Object,
    category: Object,
    categories: Object,
    current_category_id: Number,
});

const isOpenModal = ref(false);
const bulkText = ref(null);

const form = useForm({
    current_category_id: props.current_category_id ?? 1,
    id: props.category.id,
    category_name: props.category.name,
    equipments: props.category.equipments ?? [],
});

const submit = ()=>{
    form.put(route('sale-prova.equipment.update', props.room.id), {
        preserveState: false,
    });
};

const selectCategory = ()=>{
    router.get(route('sale-prova.equipment.edit', props.room.id), {current_category_id: form.current_category_id}, {
        preserveState: false
    });
};

const addEquip = ()=>{
    form.equipments.push({
        id: null,
        name: null,
        equipment_category_id: form.current_category_id
    });
};

const removeEquip = (index)=>{
    form.equipments.splice(index, 1);
};

const openModalBulk = ()=>{
    isOpenModal.value = true;
};

const closeModalBulk = ()=>{
    isOpenModal.value = false;
    bulkText.value = null;
};

const addEquipBulk = ()=>{
    if(bulkText.value){
        let arrEquipBulk = bulkText.value.split(/\r?\n/);

        arrEquipBulk.forEach(name => {
            form.equipments.push({
                name: name,
                equipment_category_id: form.current_category_id,
            });
        });
    }

    isOpenModal.value = false;
    bulkText.value = null;
};

const tabLinks = computed(()=>{
    if(props.room.id){
        return [
            {
                label: 'Descrizione',
                route: 'sale-prova.edit',
                params: props.room.id,
            },
            {
                label: 'Tariffe',
                route: 'sale-prova.prices.edit',
                params: props.room.id,
            },
            {
                label: 'Equipaggiamento',
                route: 'sale-prova.equipment.edit',
                params: props.room.id,
            },
            {
                label: 'Foto',
                route: 'sale-prova.photos.edit',
                params: props.room.id,
            },
        ];
    } else {
        return null;
    }
});

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Equipaggiamento',
    }, {default: () => page}),
};
</script>
