<template>
    <jet-form-section @submitted="createInterest">
        <template #title>
            Interessen Details
        </template>

        <template #description>
            Erstellen Sie ein Interesse das Benutzer wählen können.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus />
                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-button type="link" :href="route('interests.index')" class="mr-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Abbrechen
            </jet-button>
            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Speichern
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetTextArea from '@/Jetstream/TextArea.vue'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'

    export default {
        components: {
            JetButton,
            JetFormSection,
            JetInput,
            JetTextArea,
            JetInputError,
            JetLabel
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: ''
                })
            }
        },

        methods: {
            createInterest() {
                this.form.post(route('interests.store'), {
                    errorBag: 'createInterest',
                    preserveScroll: true
                });
            },
        },
    }
</script>
