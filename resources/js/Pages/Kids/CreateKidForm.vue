<template>
    <jet-form-section @submitted="createKid">
        <template #title>
            Kind Details
        </template>

        <template #description>
            Fügen Sie Kinder zu Familien hinzu
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus />
                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="entry_date" value="Eintrittsdatum" />
                <jet-input id="entry_date" type="date" class="mt-1 block w-full" v-model="form.entry_date" autofocus />
                <jet-input-error :message="form.errors.entry_date" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="leave_date" value="Austrittsdatum" />
                <jet-input id="leave_date" type="date" class="mt-1 block w-full" v-model="form.leave_date" autofocus />
                <jet-input-error :message="form.errors.leave_date" class="mt-2" />
            </div>
        </template>

        <template #actions>
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
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'

    export default {

        props: ['team'],

        components: {
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    entry_date: '',
                    leave_date: '',
                    team_id: this.team.id
                })
            }
        },

        methods: {
            createKid() {
                this.form.post(route('kids.store'), {
                    errorBag: 'createKid',
                    preserveScroll: true
                });
            },
        },
    }
</script>
