<template>
    <jet-form-section @submitted="updateEvent">
        <template #title>
            Aufgaben Details
        </template>

        <template #description>
            Bearbeiten Sie eine Aufgabe
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
    import dayjs from "dayjs"

    export default {
        components: {
            JetButton,
            JetFormSection,
            JetInput,
            JetTextArea,
            JetInputError,
            JetLabel,
        },

        props: ['event'],

        data() {
            return {
                form: this.$inertia.form(Object.assign({}, this.event, {
                    start_date: dayjs(this.event.start_date).format('YYYY-MM-DDThh:mm'),
                    end_date: dayjs(this.event.end_date).format('YYYY-MM-DDThh:mm')
                }))
            }
        },

        methods: {
            updateEvent() {
                this.form.put(route('events.update', {id: this.event.id}), {
                    errorBag: 'updatedEvent',
                    preserveScroll: true
                });
            },
        },
    }
</script>
