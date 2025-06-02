<?php

namespace SqlOrganize\Sql;


class Schema_ implements ISchema
{
	protected $entities = [];

	public function getEntities(): array
	{
		return $this->entities;
	}

	public function setEntities(array $entities): void
	{
		$this->entities = $entities;
	}
}