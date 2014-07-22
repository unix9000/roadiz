<?php 

namespace RZ\Renzo\Core\Entities;

use RZ\Renzo\Core\AbstractEntities\Positioned;
use RZ\Renzo\Core\AbstractEntities\Persistable;
use RZ\Renzo\Core\Entities\NodesSources;
use RZ\Renzo\Core\Entities\Document;
use RZ\Renzo\Core\Entities\NodeTypeField;

/**
 * @Entity(repositoryClass="RZ\Renzo\Core\Entities\NodesSourcesDocumentsRepository")
 * @Table(name="nodes_sources_documents")
 */
class NodesSourcesDocuments extends Positioned implements Persistable
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	private $id;
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @ManyToOne(targetEntity="RZ\Renzo\Core\Entities\NodesSources", inversedBy="documentsByFields")
	 * @JoinColumn(name="ns_id", referencedColumnName="id", onDelete="CASCADE")
	 * @var RZ\Renzo\Core\Entities\NodesSources
	 */
	private $nodeSource;


	/**
	 * @ManyToOne(targetEntity="RZ\Renzo\Core\Entities\Document")
	 * @JoinColumn(name="document_id", referencedColumnName="id", onDelete="CASCADE")
	 * @var RZ\Renzo\Core\Entities\Document
	 */
	private $document;

	/**
	 * @ManyToOne(targetEntity="RZ\Renzo\Core\Entities\NodeTypeField")
	 * @JoinColumn(name="node_type_field_id", referencedColumnName="id", onDelete="CASCADE")
	 * @var RZ\Renzo\Core\Entities\NodeTypeField
	 */
	private $field;

	/**
	 * 
	 * @param mixed        $nodeSource NodesSources and inherited types
	 * @param Document      $document  
	 * @param NodeTypeField $field 
	 */
	public function __construct( $nodeSource, Document $document, NodeTypeField $field ){

		$this->nodeSource = $nodeSource;
		$this->document = $document;
		$this->field = $field;
	}
}