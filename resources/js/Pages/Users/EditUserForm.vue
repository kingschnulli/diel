<template>
    <jet-form-section @submitted="createUser">
        <template #title>
            Benutzer Details
        </template>

        <template #description>
            Bearbeiten Sie ein Benutzerkonto
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus />
                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Email" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.email"  />
                <jet-input-error :message="form.errors.email" class="mt-2" />
            </div>
            <div class="block mt-4 col-span-6 sm:col-span-4">
                <label class="flex items-center">
                    <jet-checkbox name="admin" v-model:checked="form.admin" />
                    <span class="ml-2 text-sm text-gray-600">Ist Administrator</span>
                </label>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="team" value="Familie" />
                <Multiselect
                    v-model="form.current_team_id"
                    :options="allTeams"
                    mode="single"
                    value-prop="id"
                    track-by="id"
                    label="name"
                    searchable
                />
                <jet-input-error :message="form.errors.team" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-button type="link" :href="route('users.index')" class="mr-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
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
    import Multiselect from "@vueform/multiselect";
    import JetCheckbox from "@/Jetstream/Checkbox.vue";

    export default {
        components: {
            JetCheckbox,
            JetButton,
            JetFormSection,
            JetInput,
            JetTextArea,
            JetInputError,
            JetLabel,
            Multiselect
        },

        props: ['allInterests', 'allTeams', 'user'],

        data() {
            return {
                form: this.$inertia.form(Object.assign({}, this.user))
            }
        },

        methods: {
            createUser() {
                this.form.post(route('users.update', {id: this.user.id}), {
                    errorBag: 'updateUser',
                    preserveScroll: true
                });
            },
        },
    }
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
