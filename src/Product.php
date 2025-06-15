<?php
namespace User\Scandiweb;

class Product {
    private int $id;
    private string $sku;
    private string $name;
    private float $price;
    private string $type;
    private string $attribute;

    public function __construct(int $id, string $sku, string $name, float $price, string $type, string $attribute) {
        $this->id = $id;
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->type = $type;
        $this->attribute = $attribute;
    }

    public function getId(): int { return $this->id; }
    public function getSku(): string { return $this->sku; }
    public function getName(): string { return $this->name; }
    public function getPrice(): float { return $this->price; }
    public function getType(): string { return $this->type; }
    public function getAttribute(): string { return $this->attribute; }
}
