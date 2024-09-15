<template>
    <TransitionRoot as="template" :show="props.isOpen" >
        <Dialog as="div" @close="emit('close')" class="relative z-10">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black bg-opacity-70" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex items-center justify-center p-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel class="w-full max-w-3xl p-6 text-left align-middle transition-all transform bg-white border-2 rounded-lg shadow-xl border-emerald-600">
                            <DialogTitle as="h3" class="flex items-start justify-between text-lg text-black ">
                                <div>
                                    <span v-if="props.is_reply" class="font-normal">
                                        {{ lang.messages['Reply to'] }}:
                                    </span>

                                    <span v-else class="font-normal">
                                        {{ lang.messages['To'] }}:
                                    </span>
                                    
                                    {{ props.to.name }}
                                </div>

                                <button type="button" @click="emit('close')" class="self-end pb-1 pl-1">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </DialogTitle>

                            <form @submit.prevent="submit()" class="mt-6 space-y-4 text-sm text-black">
                                <Input id="new-message-subject" :label="lang.messages['Subject']" v-model="form.subject" class="w-full" required />
                                
                                <Textarea v-model="form.body" :rows="16" :placeholder="lang.messages['Enter your message']" :error="form.errors.body" required />

                                <div class="flex flex-col gap-2 mt-4 md:items-center md:justify-end md:flex-row">
                                    <Button type="submit" text="Send" icon="fa-solid fa-paper-plane" :disabled="form.processing" :isLoading="form.processing"/>

                                    <Button type="submit" @click="form.isDraft = true" text="Save to draft" icon="fa-solid fa-file-pen" color="sky" :disabled="form.processing" :isLoading="form.processing"/>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import Input from '@/Components/Form/Input.vue';
import Button from '@/Components/Form/Button.vue';
import Textarea from '@/Components/Form/Textarea.vue';
import { Dialog, DialogTitle, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';

const props = defineProps({
    isOpen: Boolean,
    to: Object,
    subject: String,
    body: String,
    conversation_id: String,
    msg_id: Number,
    is_reply: {
        type: Boolean,
        default: false
    },
});

const emit = defineEmits(['close']);

const lang = usePage().props.localization;

const form = useForm({
    msg_id: props.msg_id,
    to_company_id: props.to.id,
    subject: props.is_reply ? 'Re: ' + props.subject : props.subject,
    body: props.body,
    conversation_id: props.conversation_id,
    is_reply: props.is_reply,
    isDraft: false
});

const submit = ()=>{
    if(form.isDraft){
        form.post(route('messages.draft.save', props.msg_id), {
            onSuccess: ()=>{
                emit('close');
                form.reset();
            }
        });
    } else {
        if(route().current('messages.draft.index')){
            form.put(route('messages.draft.send', props.msg_id), {
                onSuccess: ()=>{
                    emit('close');
                    form.reset();
                }
            });
        } else {
            form.post(route('messages.new.send'), {
                onSuccess: ()=>{
                    emit('close');
                    form.reset();
                }
            });
        }
    }
}

</script>
