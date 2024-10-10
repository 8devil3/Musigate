<template>
    <ContentLayout
        @submitted="submit()"
        :isLoading="form.processing"
        :title="props.room.name"
        icon="fa-solid fa-sliders"
        :tabLinks="tabLinks"
        :backRoute="route('rooms.index')"
        showBackRoute
    >
        <template #content>
            <FormElement v-for="category, key in props.equipment_categories">
                <template #title>
                    {{ category }}
                </template>

                <template #description>
                    Puoi inserirli singolarmente oppure copiare un elenco di testo e incollarlo nella finestra che compare cliccando su "Aggiungi in massa".
                </template>

                <template #content>
                    <div v-if="form.equipment.filter(equip => equip.equipment_category_id == key).length" class="space-y-3">
                        <div v-for="equip, id in form.equipment.filter(equip => equip.equipment_category_id == key)" class="flex gap-2">
                            <Input v-model="equip.name" placeholder="Inserisci equipaggiamento" :error="form.errors[id + '.name']" class="w-full" required/>
                            <ActionButton @click="removeEquip(equip)" icon="fa-solid fa-trash-can" color="red" title="Rimuove l'equipaggiamento"/>
                        </div>

                        <div class="px-4 space-x-8">
                            <button type="button" @click="addEquip(key)" title="Aggiungi singolo" class="font-medium text-orange-500 transition-colors hover:text-orange-400">+ Aggiungi singolo</button>
                            <button type="button" @click="openModalBulk(category, key)" title="Aggiungi in massa" class="font-medium text-orange-500 transition-colors hover:text-orange-400">+ Aggiungi in massa</button>
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
                                <Button @click="addEquip(key)" text="Aggiungi singolo" icon="fa-solid fa-plus" title="Aggiungi singolo" class="w-full" />
                                <Button @click="openModalBulk(category, key)" text="Aggiungi in massa" icon="fa-solid fa-square-plus" title="Aggiungi in massa" class="w-full" />
                            </div>
                        </template>
                    </Empty>
                </template>
            </FormElement>
        </template>

        <template #actions>
            <SaveButton />
        </template>
    </ContentLayout>

    <Modal :isOpen="openModal" @close="closeModalBulk()">
        <template #title>{{ currentCategory.name }} - Aggiungi in massa</template>
        <template #description>
            Incolla qui l'elenco dell'equipaggiamento copiato.<br>
            Ogni singola riga corrisponde ad un equipaggiamento.
            <Textarea v-model="bulkText" class="w-full mt-4" />
        </template>
        <template #actions>
            <Button @click="addEquipBulk()" text="Ok, aggiungi" color="green" />
            <Button @click="closeModalBulk()" text="Annulla" color="gray" />
        </template>
    </Modal>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';
import Input from '@/Components/Form/Input.vue'
import Modal from '@/Components/Modal.vue';
import Button from '@/Components/Form/Button.vue';
import Textarea from '@/Components/Form/Textarea.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import ActionButton from '@/Components/Form/ActionButton.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import Empty from '@/Components/Backoffice/Empty.vue';

const props = defineProps({
    room: Object,
    equipment: Object,
    equipment_categories: Object,
});

const openModal = ref(false);
const bulkText = ref(null);
const currentCategory = ref({
    id: null,
    equipment_category_id: null,
    name: null
});

const form = useForm({
    equipment: props.equipment
});

const submit = ()=>{
    form.put(route('rooms.equipment.update', props.room.id), {
        preserveScroll: true,
    });
}

const openModalBulk = (categoryName, key)=>{
    currentCategory.value = {
        id: null,
        equipment_category_id: key,
        name: categoryName
    };
    
    openModal.value = true;
}

const closeModalBulk = ()=>{
    openModal.value = false;
    bulkText.value = null;
    currentCategory.value = {
        id: null,
        equipment_category_id: null,
        name: null
    };
}

const addEquip = (key)=>{
    form.equipment.push({
        id: null,
        name: null,
        equipment_category_id: parseInt(key)
    });
}

const addEquipBulk = ()=>{
    if(bulkText.value){
        let eqArr = bulkText.value.split(/\r?\n/);

        eqArr.forEach(eqName => {
            form.equipment.push({
                name: eqName,
                equipment_category_id: currentCategory.value.equipment_category_id,
            });
        });
    }
    
    openModal.value = false;
    bulkText.value = null;
    currentCategory.value = {
        id: null,
        equipment_category_id: null,
        name: null
    };
}

const removeEquip = (equip)=>{
    let index = form.equipment.findIndex(eq => eq.id === equip.id);
    form.equipment.splice(index, 1);
}

const tabLinks = computed(()=>{
    if(props.room.id){
        return [
            {
                name: 'Descrizione',
                href: route('sale-prova.edit', props.room.id),
                active: route().current('sale-prova.edit', props.room.id)
            },
            {
                name: 'Tariffe',
                href: route('sale-prova.prices.edit', props.room.id),
                active: route().current('sale-prova.prices.edit', props.room.id)
            },
            {
                name: 'Equipaggiamento',
                href: route('sale-prova.equipment.edit', props.room.id),
                active: route().current('sale-prova.equipment.edit', props.room.id)
            },
            {
                name: 'Foto',
                href: route('sale-prova.photos.edit', props.room.id),
                active: route().current('sale-prova.photos.edit', props.room.id)
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
