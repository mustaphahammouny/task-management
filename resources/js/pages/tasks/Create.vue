<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Drawer, DrawerContent, DrawerDescription, DrawerFooter, DrawerHeader, DrawerTitle, DrawerTrigger } from '@/components/ui/drawer';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import tasks from '@/routes/tasks';
import type { Project } from '@/types';

defineProps<{ projects: Project[] }>();

const open = ref(false);
</script>

<template>
    <Drawer v-model:open="open" swipe-direction="right">
        <DrawerTrigger as-child>
            <Button :disabled="projects.length === 0">Create task</Button>
        </DrawerTrigger>
        <DrawerContent>
            <DrawerHeader>
                <DrawerTitle>Create task</DrawerTitle>
                <DrawerDescription>Add a task to one of your projects.</DrawerDescription>
            </DrawerHeader>
            <Form :action="tasks.store()" v-slot="{ errors, processing }" class="flex min-h-0 flex-1 flex-col"
                :options="{ only: ['tasks', 'flash'], preserveState: false, preserveScroll: true }" reset-on-success @success="open = false">
                <div class="grid gap-4 overflow-y-auto px-4 py-2">
                    <div class="grid gap-2">
                        <Label for="create-task-name">Name</Label>
                        <Input id="create-task-name" name="name" placeholder="Task name" required maxlength="255"
                            :disabled="processing" :aria-invalid="!!errors.name" />
                        <InputError :message="errors.name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="create-task-priority">Priority</Label>
                        <Input id="create-task-priority" name="priority" type="number" min="1" step="1" required
                            :disabled="processing" :aria-invalid="!!errors.priority" />
                        <InputError :message="errors.priority" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="create-task-project">Project</Label>
                        <Select name="project_id" required :disabled="processing">
                            <SelectTrigger id="create-task-project" class="w-full" :aria-invalid="!!errors.project_id">
                                <SelectValue placeholder="Select a project" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="project in projects" :key="project.id" :value="String(project.id)">{{
                                    project.name }}</SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="errors.project_id" />
                    </div>
                </div>
                <DrawerFooter>
                    <Button type="submit" :disabled="processing">Create</Button>
                    <Button type="button" variant="secondary" :disabled="processing"
                        @click="open = false">Cancel</Button>
                </DrawerFooter>
            </Form>
        </DrawerContent>
    </Drawer>
</template>
