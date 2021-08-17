<template>
    <jet-form-section @submitted="createEvent">
        <template #title>
            Aufgaben Details
        </template>

        <template #description>
            Erstellen Sie eine Aufgabe an der andere Teilnehmen können.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus />
                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-2">
                <jet-label for="quota" value="Kontingent" />
                <jet-input id="quota" type="number" min="1" step="0.5" class="mt-1 block w-full" v-model="form.quota" autofocus />
                <jet-input-error :message="form.errors.quota" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <jet-label for="start_date" value="Beginn" />
                <jet-input id="start_date" type="datetime-local" class="mt-1 block w-full" v-model="form.start_date" autofocus />
                <jet-input-error :message="form.errors.start_date" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <jet-label for="end_date" value="Ende" />
                <jet-input id="end_date" type="datetime-local" class="mt-1 block w-full" v-model="form.end_date" autofocus />
                <jet-input-error :message="form.errors.end_date" class="mt-2" />
            </div>
            <div class="col-span-6">
                <jet-label for="description" value="Kurzbeschreibung" />
                <jet-input id="description" type="text" class="mt-1 block w-full" v-model="form.description" autofocus />
                <jet-input-error :message="form.errors.description" class="mt-2" />
            </div>
            <div class="col-span-6">
                <jet-label for="long_description" value="Beschreibung" />
                <jet-text-area id="long_description" min="1" step="0.5" class="mt-1 block w-full" v-model="form.long_description" autofocus />
                <jet-input-error :message="form.errors.long_description" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-button type="link" :href="route('events.index')" class="mr-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
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
            JetLabel,
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    start_date: new Date(),
                    end_date: new Date(),
                    quota: 1,
                    description: '',
                    long_description: '',
                })
            }
        },

        methods: {
            createEvent() {
                this.form.post(route('events.store'), {
                    errorBag: 'createEvent',
                    preserveScroll: true
                });
            },
        },
    }
</script>
