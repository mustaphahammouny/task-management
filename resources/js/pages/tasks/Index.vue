<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { VueDraggable } from 'vue-draggable-plus'
import tasks from '@/routes/tasks';
import { GripVertical, X } from '@lucide/vue';
import { Badge } from '@/components/ui/badge';
import { Empty, EmptyDescription, EmptyHeader, EmptyTitle } from '@/components/ui/empty';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import type { Project, Task } from '@/types';
import Create from './Create.vue';
import Delete from './Delete.vue';
import Edit from './Edit.vue';
import { ref, watch } from 'vue';
import { router } from "@inertiajs/vue3";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { pickBy } from 'lodash';

defineOptions({
    layout: { breadcrumbs: [{ title: 'Tasks', href: tasks.index() }] },
});

const page = usePage<{ query?: { project_id?: number } }>();

const props = defineProps<{
    tasks: Task[];
    projects: Project[];
}>();

const reorderedTasks = ref<Task[]>([...props.tasks]);
const selectedProjectId = ref<string | null>(page.props.query?.project_id?.toString() ?? null);

watch(
    selectedProjectId,
    () => {
        router.get(tasks.index(), pickBy({
            project_id: selectedProjectId.value,
        }), {
            preserveState: false,
            preserveScroll: true,
            only: ['tasks', 'query'],
        });
    }
);

const reorder = () => {
    router.post(tasks.reorder(), {
        reordered_ids: reorderedTasks.value.map((task: Task) => task.id),
    }, {
        preserveState: false,
        preserveScroll: true,
        only: ['tasks', 'flash'],
    });
};
</script>

<template>
    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">

        <Head title="Tasks" />
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Tasks</h1>
                <p class="text-sm text-muted-foreground">Manage the tasks in your projects.</p>
            </div>
            <Create :projects="props.projects" />
        </div>
        <div class="flex w-full items-center justify-end gap-2">
            <Select v-model="selectedProjectId">
                <div class="w-[300px]">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Select a project" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectLabel>Projects</SelectLabel>
                            <SelectItem :value="null">
                                All projects
                            </SelectItem>
                            <SelectItem v-for="project in props.projects" :key="project.id"
                                :value="project.id.toString()">
                                {{ project.name }}
                            </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </div>
            </Select>
        </div>
        <Empty v-if="props.tasks.length === 0" class="border">
            <EmptyHeader>
                <EmptyTitle>No tasks yet</EmptyTitle>
                <EmptyDescription>
                    Create your first task to get started.
                </EmptyDescription>
            </EmptyHeader>
        </Empty>
        <div v-else class="overflow-hidden rounded-lg border">
            <VueDraggable v-model="reorderedTasks" target="tbody" :animation="150" @end="reorder">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead></TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Project</TableHead>
                            <TableHead>Priority</TableHead>
                            <TableHead>Created at</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="task in reorderedTasks" :key="task.id">
                            <TableCell>
                                <GripVertical class="drag-handle cursor-grab" />
                            </TableCell>
                            <TableCell class="font-medium">{{ task.name }}</TableCell>
                            <TableCell>{{ task.project?.name ?? '—' }}</TableCell>
                            <TableCell>
                                <Badge variant="secondary">{{ task.priority }}</Badge>
                            </TableCell>
                            <TableCell class="whitespace-nowrap">{{ task.created_at }}</TableCell>
                            <TableCell>
                                <div class="flex justify-end gap-2">
                                    <Edit :task="task" />
                                    <Delete :task="task" />
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </VueDraggable>
        </div>
    </div>
</template>
