<?php

use Modules\Tree\App\Models\TreeBranch;

function getTreePath(string $tree_name): string
{
    return public_path("modules/tree/{$tree_name}");
}

function generateBranchStage(int $parent_id): int
{
    $parentBranch = TreeBranch::find($parent_id);
    return $parentBranch ? $parentBranch->stage + 1 : 1;
}

function generateBranchCode(?string $parentCode = null, array $existingChildCodes = [], ?int $organization_id = null): string
{
    if (is_null($parentCode)) {
        $branchCount = TreeBranch::whereOrganizationId($organization_id)->whereNull('parent_id')->count();
        return sprintf('%05d', $branchCount + 1);
    }

    $position = count($existingChildCodes) + 1;
    do {
        $proposedCode = sprintf('%s%05d', $parentCode, $position++);
    } while (in_array($proposedCode, $existingChildCodes));

    return $proposedCode;
}

function generateNewBranchCode(?int $parent_id = null): string
{
    $parentCode = $parent_id ? TreeBranch::find($parent_id)->code : null;
    $existingChildCodes = TreeBranch::where('parent_id', $parent_id)->pluck('code')->toArray();
    return generateBranchCode($parentCode, $existingChildCodes);
}

function buildParentChain($model, $relation = 'parent')
{
    $current = $model;
    $chain = [];

    while ($current) {
        $chain[] = $current;
        $current = $current->$relation; // Ensure you have `public function parent()` relation
    }
    return array_reverse($chain); // From root to leaf
}

function buildChildStructure(array $rows, $resource = null)
{

    $root = null;

    foreach (array_reverse($rows) as $row) {
        $resource =  new $resource($row);
        $resource->child = $root;
        $root = $resource;
    }

    return $root;
}
