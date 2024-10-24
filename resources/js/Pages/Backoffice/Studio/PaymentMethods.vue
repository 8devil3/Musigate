<template>
    <ContentLayout
        @submitted="submit()"
        title="Metodi di pagamento"
        icon="fa-regular fa-credit-card"
    >
        <template #content>
            <FormElement>
                <template #description>
                    Spunta i metodi di pagamento accettati dallo Studio.
                </template>

                <template #content>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-6 md:grid-cols-2 xl:grid-cols-3">
                        <Checkbox v-for="payment, key in props.all_payments" v-model="form.payments" :value="parseInt(key)" :id="'edit-studio-payment-' + key">
                            <img :src="'/img/payments/' + payment.img_name" :alt="payment.name" class="inline h-5 mr-1" :title="payment.name">
                            {{ payment.name }}
                        </Checkbox>
                    </div>
                </template>
            </FormElement>
        </template>
        
        <template v-if="form.isDirty && !form.processing" #actions>
            <Button @click="form.reset()" text="Annulla" color="slate" icon="fa-solid fa-arrow-rotate-left" />
            <SaveButton />
        </template>
    </ContentLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import Button from '@/Components/Form/Button.vue';
import SaveButton from '@/Components/Form/SaveButton.vue';
import Checkbox from '@/Components/Form/Checkbox.vue';
import FormElement from '@/Components/Backoffice/FormElement.vue';
import ContentLayout from '@/Layouts/Backoffice/ContentLayout.vue';

const props = defineProps({
    payments: Object,
    all_payments: Object,
})

const form = useForm({
    payments: props.payments,
});

const submit = () => {
    form.put(route('studio.payment_methods.update'), {
        preserveState: false,
    });
};

</script>

<script>
import BackofficeLayout from '@/Layouts/Backoffice/BackofficeLayout.vue';

export default {
    layout: (h, page) => h(BackofficeLayout, {
        title: 'Metodi di pagamento',
    }, {default: () => page}),
};
</script>
