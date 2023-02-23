<template>
    <jet-form-section @submitted="updateTeamName">
        <template #title>
            Familien Name
        </template>

        <template #description>
            Name der Familie und Inhaber Informationen.
        </template>

        <template #form>
            <!-- Team Owner Information -->
            <div class="col-span-6">
                <jet-label value="Familien Inhaber" />

                <div class="flex items-center mt-2">
                    <img class="w-12 h-12 rounded-full object-cover" :src="team.owner.profile_photo_url" :alt="team.owner.name">

                    <div class="ml-4 leading-tight">
                        <div>{{ team.owner.name }}</div>
                        <div class="text-gray-700 text-sm">{{ team.owner.email }}</div>
                    </div>
                </div>
            </div>

            <!-- Team Name -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Familien Name" />

                <jet-input id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            :disabled="! permissions.canUpdateTeam" />

                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6">
                <div class="flex">
                    <jet-label for="name" value="Kinder" />
                    <jet-button v-if="$page.props.user.admin" class="ml-auto" type="link" :href="route('kids.create', {team: team.id})">+ Kind hinzufügen</jet-button>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
                    <Table
                        :meta="team.kids"
                        ref="table"
                    >
                        <template #tableColumns>

                        </template>
                        <template #head>
                            <tr>
                                <th>Name</th>
                                <th>Eintritt</th>
                                <th>Austritt</th>
                                <th></th>
                            </tr>
                        </template>

                        <template #body>
                            <tr v-for="kid in team.kids" :key="kid.id">
                                <td>{{ kid.name }}</td>
                                <td>{{ formatDateString(kid.entry_date) }}</td>
                                <td>{{ formatDateString(kid.leave_date) }}</td>
                                <td>
                                    <jet-button v-if="$page.props.user.admin" class="ml-auto bg-red-400 hover:bg-red-500 active:bg-red-500" @click.prevent="deleteKid(kid.id)">&times;</jet-button>
                                </td>
                            </tr>
                        </template>
                    </Table>
                </div>
            </div>

        </template>

        <template #actions v-if="permissions.canUpdateTeam">
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Gespeichert.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Speichern
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import {Tailwind2} from "@protonemedia/inertiajs-tables-laravel-query-builder";

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            Table: Tailwind2.Table,
        },

        props: ['team', 'permissions'],

        data() {
            return {
                form: this.$inertia.form({
                    name: this.team.name,
                    num_children: this.team.num_children,
                })
            }
        },

        methods: {
            updateTeamName() {
                this.form.put(route('teams.update', this.team), {
                    errorBag: 'updateTeamName',
                    preserveScroll: true
                });
            },
            formatDateString(dateString) {
                return dateString ? new Date(dateString).toLocaleDateString() : '';
            },
            deleteKid(id) {
                this.$inertia.delete(this.route('kids.destroy', {id: id}));
            }
        },
    }
</script>
