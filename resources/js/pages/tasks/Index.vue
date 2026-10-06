<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { index } from '@/routes/tasks';
import { Badge } from '@/components/ui/badge';
import { Empty, EmptyDescription, EmptyHeader, EmptyTitle } from '@/components/ui/empty';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import type { Project, Task } from '@/types';
import Create from './Create.vue';
import Delete from './Delete.vue';
import Edit from './Edit.vue';

defineOptions({
    layout: { breadcrumbs: [{ title: 'Tasks', href: index() }] },
});

defineProps<{
    tasks: Task[];
    projects: Project[];
}>();
</script>

<template>
    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">

        <Head title="Tasks" />
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Tasks</h1>
                <p class="text-sm text-muted-foreground">Manage the tasks in your projects.</p>
            </div>
            <Create :projects="projects" />
        </div>
        <p v-if="projects.length === 0" class="text-sm text-muted-foreground">Create a project before adding your first
            task.</p>
        <Empty v-if="tasks.length === 0" class="border">
            <EmptyHeader>
                <EmptyTitle>No tasks yet</EmptyTitle>
                <EmptyDescription>
                    {{ projects.length === 0 ? 'Create a project before adding your first task.' : 'Create your first task to get started.' }}
                </EmptyDescription>
            </EmptyHeader>
        </Empty>
        <div v-else class="overflow-hidden rounded-lg border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Project</TableHead>
                        <TableHead>Priority</TableHead>
                        <TableHead>Created at</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="task in tasks" :key="task.id">
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
        </div>
    </div>
</template>
